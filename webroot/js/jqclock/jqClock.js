/* Sets time in clock div and calls itself every second */
/**
 * Clock plugin
 * Copyright (c) 2010 John R D'Orazio (donjohn.fmmi@gmail.com)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 * 
 * Turns a jQuery dom element into a dynamic clock
 *  
 * @timestamp defaults to clients current time
 *   $("#mydiv").clock();
 *   >> will turn div into clock using client computer's current time
 * @timestamp server-side example:
 *   Say we have a hidden input with id='timestmp' the value of which is determined server-side with server's current time
 *   $("#mydiv").clock({"timestamp":$("#timestmp").val()});
 *   >> will turn div into clock passing in server's current time as retrieved from hidden input
 *    
 * @format defaults to 12 hour format,
 *   or if langSet is indicated defaults to most appropriate format for that langSet
 *   $("#mydiv").clock(); >> will have 12 hour format
 *   $("#mydiv").clock({"langSet":"it"}); >> will have 24 hour format
 *   $("#mydiv").clock({"langSet":"en"}); >> will have 12 hour format 
 *   $("#mydiv").clock({"langSet":"en","format":"24"}); >> will have military style 24 hour format
 *   $("#mydiv").clock({"calendar":true}); >> will include the date with the time, and will update the date at midnight
 *         
 */

(function($, undefined) {

$.clock = { version: "2.1.1"}

t = new Array();
  
$.fn.clock = function(options) {
  return this.each(function(){
    options = options || {};  
    options.timestamp = options.timestamp || "systime";
    systimestamp = new Date();
    systimestamp = systimestamp.getTime();
    options.sysdiff = 0;
    if(options.timestamp!="systime"){
      mytimestamp = new Date(options.timestamp);
      options.sysdiff = options.timestamp - systimestamp;
    }
    options.format = options.format || ((options.langSet!="en") ? "24" : "12");
    options.calendar = options.calendar || "true";

    if (!$(this).hasClass("jqclock")){$(this).addClass("jqclock");}

    var addleadingzero = function(i){
      if (i<10){i="0" + i;}
      return i;    
    },
    updateClock = function(el,myoptions) {
      var el_id = $(el).attr("id");
      if(myoptions=="destroy"){ clearTimeout(t[el_id]); }
      else {
        mytimestamp = new Date();
        mytimestamp = mytimestamp.getTime();
        mytimestamp = mytimestamp + myoptions.sysdiff;
        mytimestamp = new Date(mytimestamp);
        calend="";
        if(myoptions.calendar!="false") {
            calend = "<span class='clockdate'>"+mytimestamp.format(myoptions.datestr) + "</span>";
        }
        $(el).html(calend+"<span class='clocktime'>"+mytimestamp.format(myoptions.timestr) +"</span>");
        t[el_id] = setTimeout(function() { updateClock( $(el),myoptions ) }, 1000);
      }

    }
      
    updateClock($(this),options);
  });
}

  return this;

})(jQuery);
