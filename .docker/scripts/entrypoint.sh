#!/bin/bash
set -e

if [ -n "$JOOMLA_DB_PASSWORD_FILE" ] && [ -f "$JOOMLA_DB_PASSWORD_FILE" ]; then
  JOOMLA_DB_PASSWORD=$(cat "$JOOMLA_DB_PASSWORD_FILE")
fi

if [[ "$1" == apache2* ]] || [ "$1" == php-fpm ]; then
  uid="$(id -u)"
  gid="$(id -g)"
  if [ "$uid" = '0' ]; then
    case "$1" in
    apache2*)
      user="${APACHE_RUN_USER:-www-data}"
      group="${APACHE_RUN_GROUP:-www-data}"

      # strip off any '#' symbol ('#1000' is valid syntax for Apache)
      pound='#'
      user="${user#$pound}"
      group="${group#$pound}"

      # set user if not exist
      if ! id "$user" &>/dev/null; then
        # get the user name
        : "${USER_NAME:=www-data}"
        # change the user name
        [[ "$USER_NAME" != "www-data" ]] &&
          usermod -l "$USER_NAME" www-data &&
          groupmod -n "$USER_NAME" www-data
        # update the user ID
        groupmod -o -g "$user" "$USER_NAME"
        # update the user-group ID
        usermod -o -u "$group" "$USER_NAME"
      fi
      ;;
    *) # php-fpm
      user='www-data'
      group='www-data'
      ;;
    esac
  else
    user="$uid"
    group="$gid"
  fi

  if [ -n "$MYSQL_PORT_3306_TCP" ]; then
    if [ -z "$JOOMLA_DB_HOST" ]; then
      JOOMLA_DB_HOST='mysql'
    else
      echo >&2 "warning: both JOOMLA_DB_HOST and MYSQL_PORT_3306_TCP found"
      echo >&2 "  Connecting to JOOMLA_DB_HOST ($JOOMLA_DB_HOST)"
      echo >&2 "  instead of the linked mysql container"
    fi
  fi

  if [ -z "$JOOMLA_DB_HOST" ]; then
    echo >&2 "error: missing JOOMLA_DB_HOST and MYSQL_PORT_3306_TCP environment variables"
    echo >&2 "  Did you forget to --link some_mysql_container:mysql or set an external db"
    echo >&2 "  with -e JOOMLA_DB_HOST=hostname:port?"
    exit 1
  fi

  # If the DB user is 'root' then use the MySQL root password env var
  : "${JOOMLA_DB_USER:=root}"
  if [ "$JOOMLA_DB_USER" = 'root' ]; then
    : ${JOOMLA_DB_PASSWORD:=$MYSQL_ENV_MYSQL_ROOT_PASSWORD}
  fi
  : "${JOOMLA_DB_NAME:=joomla}"

  if [ -z "$JOOMLA_DB_PASSWORD" ] && [ "$JOOMLA_DB_PASSWORD_ALLOW_EMPTY" != 'yes' ]; then
    echo >&2 "error: missing required JOOMLA_DB_PASSWORD environment variable"
    echo >&2 "  Did you forget to -e JOOMLA_DB_PASSWORD=... ?"
    echo >&2
    echo >&2 "  (Also of interest might be JOOMLA_DB_USER and JOOMLA_DB_NAME.)"
    exit 1
  fi

  if [ ! -e index.php ] && [ ! -e libraries/src/Version.php ]; then
    # if the directory exists and Joomla doesn't appear to be installed AND the permissions of it are root:root, let's chown it (likely a Docker-created directory)
    if [ "$uid" = '0' ] && [ "$(stat -c '%u:%g' .)" = '0:0' ]; then
      chown "$user:$group" .
    fi

    echo >&2 "Joomla not found in $PWD - copying now..."
    if [ "$(ls -A)" ]; then
      echo >&2 "WARNING: $PWD is not empty - press Ctrl+C now if this is an error!"
      (
        set -x
        ls -A
        sleep 10
      )
    fi
    # use full commands
    # for clearer intent
    sourceTarArgs=(
      --create
      --file -
      --directory /usr/src/joomla
      --one-file-system
      --owner "$user" --group "$group"
    )
    targetTarArgs=(
      --extract
      --file -
    )
    if [ "$uid" != '0' ]; then
      # avoid "tar: .: Cannot utime: Operation not permitted" and "tar: .: Cannot change mode to rwxr-xr-x: Operation not permitted"
      targetTarArgs+=(--no-overwrite-dir)
    fi

    tar "${sourceTarArgs[@]}" . | tar "${targetTarArgs[@]}"

    if [ ! -e .htaccess ]; then
      # NOTE: The "Indexes" option is disabled in the php:apache base image so remove it as we enable .htaccess
      sed -r 's/^(Options -Indexes.*)$/#\1/' htaccess.txt >.htaccess
      chown "$user":"$group" .htaccess
    fi

    echo >&2 "Complete! Joomla has been successfully copied to $PWD"

    echo >&2 "Joomla installation in progress..."

    php installation/joomla.php install --site-name="$TCHOOZ_SITENAME" --admin-user="$TCHOOZ_SYSADMIN_LAST_NAME $TCHOOZ_SYSADMIN_FIRST_NAME" --admin-username="$TCHOOZ_SYSADMIN_USERNAME" --admin-password="$TCHOOZ_SYSADMIN_PASSWORD" --admin-email="$TCHOOZ_SYSADMIN_MAIL" --db-type=mysql --db-host="$JOOMLA_DB_HOST" --db-user="$JOOMLA_DB_USER" --db-pass="$JOOMLA_DB_PASSWORD" --db-name="$JOOMLA_DB_NAME" --db-prefix="jos_" -n
  fi

  # Ensure the MySQL Database is created
  php /makedb.php "$JOOMLA_DB_HOST" "$JOOMLA_DB_USER" "$JOOMLA_DB_PASSWORD" "$JOOMLA_DB_NAME" "${JOOMLA_DB_TYPE:-mysqli}"

  if [ ! -e configuration.php ] && [ -d ".docker/installation/" ]; then

    echo >&2 "========================================================================"
    echo >&2
    echo >&2 "We prepare your installation..."
    echo >&2
    echo >&2 "========================================================================"

    echo >&2 "Init configuration variables..."
    cp configuration.php.dist configuration.php
    cp htaccess.txt .htaccess

    sed -i "s:\$host = '.*':\$host = '$JOOMLA_DB_HOST':g" configuration.php
    sed -i "s:\$user = '.*':\$user = '$JOOMLA_DB_USER':g" configuration.php
    sed -i "s:\$password = '.*':\$password = '$JOOMLA_DB_PASSWORD':g" configuration.php
    sed -i "s:\$db = '.*':\$db = '$JOOMLA_DB_NAME':g" configuration.php

    chown www-data: configuration.php

    php cli/joomla.php config:set sitename="$SITENAME" dbtype="mysqli" sef_rewrite=true

    echo >&2 "Init database..."

    php cli/joomla.php database:import --folder=".docker/installation/vanilla" -n

    echo >&2 "Create super administrator user..."

    php cli/joomla.php user:add --username="sysadmin" --name="ADMINISTRATOR Emundus" --password="password" --email="sysadmin@emundus.fr" --usergroup="Registered,Super Users" -n

    echo >&2 "Set Fabrik connection..."

    php cli/joomla.php tchooz:fabrik_connection_reset -n

    chown www-data: configuration.php
    chown www-data: .htaccess

    echo >&2 "========================================================================"
    echo >&2
    echo >&2 "Awesome ! Your Tchooz website is ready !"
    echo >&2
    echo >&2 "========================================================================"
  fi

  echo >&2 "========================================================================"
  echo >&2
  echo >&2 "This server is now configured to run Joomla!"
  echo >&2
  echo >&2 "NOTE: You will need your database server address, database name,"
  echo >&2 "and database user credentials to install Joomla."
  echo >&2
  echo >&2 "========================================================================"
fi

exec "$@"
