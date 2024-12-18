/*! Fabrik */

Date.CultureInfo={name:"ru-RU",englishName:"Russian (Russia)",nativeName:"русский (Россия)",dayNames:["воскресенье","понедельник","вторник","среда","четверг","пятница","суббота"],abbreviatedDayNames:["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],shortestDayNames:["Вс","Пн","Вт","Ср","Чт","Пт","Сб"],firstLetterDayNames:["В","П","В","С","Ч","П","С"],monthNames:["Январь","Февраль","Март","Апрель","Май","Июнь","Июль","Август","Сентябрь","Октябрь","Ноябрь","Декабрь"],abbreviatedMonthNames:["янв","фев","мар","апр","май","июн","июл","авг","сен","окт","ноя","дек"],amDesignator:"",pmDesignator:"",firstDayOfWeek:1,twoDigitYearMax:2029,dateElementOrder:"dmy",formatPatterns:{shortDate:"dd.MM.yyyy",longDate:"d MMMM yyyy 'г.'",shortTime:"H:mm",longTime:"H:mm:ss",fullDateTime:"d MMMM yyyy 'г.' H:mm:ss",sortableDateTime:"yyyy-MM-ddTHH:mm:ss",universalSortableDateTime:"yyyy-MM-dd HH:mm:ssZ",rfc1123:"ddd, dd MMM yyyy HH:mm:ss GMT",monthDay:"MMMM dd",yearMonth:"MMMM yyyy 'г.'"},regexPatterns:{jan:/^янв(арь)?/i,feb:/^фев(раль)?/i,mar:/^мар(т)?/i,apr:/^апр(ель)?/i,may:/^май/i,jun:/^июн(ь)?/i,jul:/^июл(ь)?/i,aug:/^авг(уст)?/i,sep:/^сен(тябрь)?/i,oct:/^окт(ябрь)?/i,nov:/^ноя(брь)?/i,dec:/^дек(абрь)?/i,sun:/^воскресенье/i,mon:/^понедельник/i,tue:/^вторник/i,wed:/^среда/i,thu:/^четверг/i,fri:/^пятница/i,sat:/^суббота/i,future:/^next/i,past:/^last|past|prev(ious)?/i,add:/^(\+|aft(er)?|from|hence)/i,subtract:/^(\-|bef(ore)?|ago)/i,yesterday:/^yes(terday)?/i,today:/^t(od(ay)?)?/i,tomorrow:/^tom(orrow)?/i,now:/^n(ow)?/i,millisecond:/^ms|milli(second)?s?/i,second:/^sec(ond)?s?/i,minute:/^mn|min(ute)?s?/i,hour:/^h(our)?s?/i,week:/^w(eek)?s?/i,month:/^m(onth)?s?/i,day:/^d(ay)?s?/i,year:/^y(ear)?s?/i,shortMeridian:/^(a|p)/i,longMeridian:/^(a\.?m?\.?|p\.?m?\.?)/i,timezone:/^((e(s|d)t|c(s|d)t|m(s|d)t|p(s|d)t)|((gmt)?\s*(\+|\-)\s*\d\d\d\d?)|gmt|utc)/i,ordinalSuffix:/^\s*(st|nd|rd|th)/i,timeContext:/^\s*(\:|a(?!u|p)|p)/i},timezones:[{name:"UTC",offset:"-000"},{name:"GMT",offset:"-000"},{name:"EST",offset:"-0500"},{name:"EDT",offset:"-0400"},{name:"CST",offset:"-0600"},{name:"CDT",offset:"-0500"},{name:"MST",offset:"-0700"},{name:"MDT",offset:"-0600"},{name:"PST",offset:"-0800"},{name:"PDT",offset:"-0700"}]};