'use strict';
void function () {

    insignia(ty);
    insignia(custom, {
        delimiter: ','
    });
    insignia(del, {deletion: true});
    insignia(def);
    insignia(lng);
    insignia(dup, {
        validate: function () {
            return true;
        }
    });

    function events(el, type, fn) {
        if (el.addEventListener) {
            el.addEventListener(type, fn);
        } else if (el.attachEvent) {
            el.attachEvent('on' + type, wrap(fn));
        } else {
            el['on' + type] = wrap(fn);
        }
        function wrap(originalEvent) {
            var e = originalEvent || global.event;
            e.target = e.target || e.srcElement;
            e.preventDefault = e.preventDefault || function preventDefault() {
                    e.returnValue = false;
                };
            e.stopPropagation = e.stopPropagation || function stopPropagation() {
                    e.cancelBubble = true;
                };
            fn.call(el, e);
        }
    }
}();