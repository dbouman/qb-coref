/*!
 * jQuery.selection - jQuery Plugin
 *
 * Copyright (c) 2010-2012 IWASAKI Koji (@madapaja).
 * http://blog.madapaja.net/
 * Under The MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
(function($, win, doc) {
    /**
     * è¦�ç´ ã�®æ–‡å­—åˆ—é�¸æŠžçŠ¶æ…‹ã‚’å�–å¾—ã�—ã�¾ã�™
     *
     * @param   {Element}   element         å¯¾è±¡è¦�ç´ 
     * @return  {Object}    return
     * @return  {String}    return.text     é�¸æŠžã�•ã‚Œã�¦ã�„ã‚‹æ–‡å­—åˆ—
     * @return  {Integer}   return.start    é�¸æŠžé–‹å§‹ä½�ç½®
     * @return  {Integer}   return.end      é�¸æŠžçµ‚äº†ä½�ç½®
     */
    var _getCaretInfo = function(element){
        var res = {
            text: '',
            start: 0,
            end: 0
        };

        if (!element.value) {
            /* å€¤ã�Œã�ªã�„ã€�ã‚‚ã�—ã��ã�¯ç©ºæ–‡å­—åˆ— */
            return res;
        }

        try {
            if (win.getSelection) {
                /* IE ä»¥å¤– */
                res.start = element.selectionStart;
                res.end = element.selectionEnd;
                res.text = element.value.slice(res.start, res.end);
            } else if (doc.selection) {
                /* for IE */
                element.focus();

                var range = doc.selection.createRange(),
                    range2 = doc.body.createTextRange(),
                    tmpLength;

                res.text = range.text;

                try {
                    range2.moveToElementText(element);
                    range2.setEndPoint('StartToStart', range);
                } catch (e) {
                    range2 = element.createTextRange();
                    range2.setEndPoint('StartToStart', range);
                }

                res.start = element.value.length - range2.text.length;
                res.end = res.start + range.text.length;
            }
        } catch (e) {
            /* ã�‚ã��ã‚‰ã‚�ã‚‹ */
        }

        return res;
    };

    /**
     * è¦�ç´ ã�«å¯¾ã�™ã‚‹ã‚­ãƒ£ãƒ¬ãƒƒãƒˆæ“�ä½œ
     * @type {Object}
     */
    var _CaretOperation = {
        /**
         * è¦�ç´ ã�®ã‚­ãƒ£ãƒ¬ãƒƒãƒˆä½�ç½®ã‚’å�–å¾—ã�—ã�¾ã�™
         *
         * @param   {Element}   element         å¯¾è±¡è¦�ç´ 
         * @return  {Object}    return
         * @return  {Integer}   return.start    é�¸æŠžé–‹å§‹ä½�ç½®
         * @return  {Integer}   return.end      é�¸æŠžçµ‚äº†ä½�ç½®
         */
        getPos: function(element) {
            var tmp = _getCaretInfo(element);
            return {start: tmp.start, end: tmp.end};
        },

        /**
         * è¦�ç´ ã�®ã‚­ãƒ£ãƒ¬ãƒƒãƒˆä½�ç½®ã‚’è¨­å®šã�—ã�¾ã�™
         *
         * @param   {Element}   element         å¯¾è±¡è¦�ç´ 
         * @param   {Object}    toRange         è¨­å®šã�™ã‚‹ã‚­ãƒ£ãƒ¬ãƒƒãƒˆä½�ç½®
         * @param   {Integer}   toRange.start   é�¸æŠžé–‹å§‹ä½�ç½®
         * @param   {Integer}   toRange.end     é�¸æŠžçµ‚äº†ä½�ç½®
         * @param   {String}    caret           ã‚­ãƒ£ãƒ¬ãƒƒãƒˆãƒ¢ãƒ¼ãƒ‰ "keep" | "start" | "end" ã�®ã�„ã�šã‚Œã�‹
         */
        setPos: function(element, toRange, caret) {
            caret = this._caretMode(caret);

            if (caret == 'start') {
                toRange.end = toRange.start;
            } else if (caret == 'end') {
                toRange.start = toRange.end;
            }

            element.focus();
            try {
                if (element.createTextRange) {
                    var range = element.createTextRange();

                    if (win.navigator.userAgent.toLowerCase().indexOf("msie") >= 0) {
                        toRange.start = element.value.substr(0, toRange.start).replace(/\r/g, '').length;
                        toRange.end = element.value.substr(0, toRange.end).replace(/\r/g, '').length;
                    }

                    range.collapse(true);
                    range.moveStart('character', toRange.start);
                    range.moveEnd('character', toRange.end - toRange.start);

                    range.select();
                } else if (element.setSelectionRange) {
                    element.setSelectionRange(toRange.start, toRange.end);
                }
            } catch (e) {
                /* ã�‚ã��ã‚‰ã‚�ã‚‹ */
            }
        },

        /**
         * è¦�ç´ å†…ã�®é�¸æŠžæ–‡å­—åˆ—ã‚’å�–å¾—ã�—ã�¾ã�™
         *
         * @param   {Element}   element         å¯¾è±¡è¦�ç´ 
         * @return  {String}    return          é�¸æŠžæ–‡å­—åˆ—
         */
        getText: function(element) {
            return _getCaretInfo(element).text;
        },

        /**
         * ã‚­ãƒ£ãƒ¬ãƒƒãƒˆãƒ¢ãƒ¼ãƒ‰ã‚’é�¸æŠžã�—ã�¾ã�™
         *
         * @param   {String}    caret           ã‚­ãƒ£ãƒ¬ãƒƒãƒˆãƒ¢ãƒ¼ãƒ‰
         * @return  {String}    return          "keep" | "start" | "end" ã�®ã�„ã�šã‚Œã�‹
         */
        _caretMode: function(caret) {
            caret = caret || "keep";
            if (caret == false) {
                caret = 'end';
            }

            switch (caret) {
                case 'keep':
                case 'start':
                case 'end':
                    break;

                default:
                    caret = 'keep';
            }

            return caret;
        },

        /**
         * é�¸æŠžæ–‡å­—åˆ—ã‚’ç½®ã��æ�›ã�ˆã�¾ã�™
         *
         * @param   {Element}   element         å¯¾è±¡è¦�ç´ 
         * @param   {String}    text            ç½®ã��æ�›ã�ˆã‚‹æ–‡å­—åˆ—
         * @param   {String}    caret           ã‚­ãƒ£ãƒ¬ãƒƒãƒˆãƒ¢ãƒ¼ãƒ‰ "keep" | "start" | "end" ã�®ã�„ã�šã‚Œã�‹
         */
        replace: function(element, text, caret) {
            var tmp = _getCaretInfo(element),
                orig = element.value,
                pos = $(element).scrollTop(),
                range = {start: tmp.start, end: tmp.start + text.length};

            element.value = orig.substr(0, tmp.start) + text + orig.substr(tmp.end);

            $(element).scrollTop(pos);
            this.setPos(element, range, caret);
        },

        /**
         * æ–‡å­—åˆ—ã‚’é�¸æŠžæ–‡å­—åˆ—ã�®å‰�ã�«æŒ¿å…¥ã�—ã�¾ã�™
         *
         * @param   {Element}   element         å¯¾è±¡è¦�ç´ 
         * @param   {String}    text            æŒ¿å…¥æ–‡å­—åˆ—
         * @param   {String}    caret           ã‚­ãƒ£ãƒ¬ãƒƒãƒˆãƒ¢ãƒ¼ãƒ‰ "keep" | "start" | "end" ã�®ã�„ã�šã‚Œã�‹
         */
        insertBefore: function(element, text, caret) {
            var tmp = _getCaretInfo(element),
                orig = element.value,
                pos = $(element).scrollTop(),
                range = {start: tmp.start + text.length, end: tmp.end + text.length};

            element.value = orig.substr(0, tmp.start) + text + orig.substr(tmp.start);

            $(element).scrollTop(pos);
            this.setPos(element, range, caret);
        },

        /**
         * æ–‡å­—åˆ—ã‚’é�¸æŠžæ–‡å­—åˆ—ã�®å¾Œã�«æŒ¿å…¥ã�—ã�¾ã�™
         *
         * @param   {Element}   element         å¯¾è±¡è¦�ç´ 
         * @param   {String}    text            æŒ¿å…¥æ–‡å­—åˆ—
         * @param   {String}    caret           ã‚­ãƒ£ãƒ¬ãƒƒãƒˆãƒ¢ãƒ¼ãƒ‰ "keep" | "start" | "end" ã�®ã�„ã�šã‚Œã�‹
         */
        insertAfter: function(element, text, caret) {
            var tmp = _getCaretInfo(element),
                orig = element.value,
                pos = $(element).scrollTop(),
                range = {start: tmp.start, end: tmp.end};

            element.value = orig.substr(0, tmp.end) + text + orig.substr(tmp.end);

            $(element).scrollTop(pos);
            this.setPos(element, range, caret);
        }
    };

    /* jQuery.selection ã‚’è¿½åŠ  */
    $.extend({
        /**
         * ã‚¦ã‚£ãƒ³ãƒ‰ã‚¦ã�®é�¸æŠžã�•ã‚Œã�¦ã�„ã‚‹æ–‡å­—åˆ—ã‚’å�–å¾—
         *
         * @param   {String}    mode            é�¸æŠžãƒ¢ãƒ¼ãƒ‰ "text" | "html" ã�®ã�„ã�šã‚Œã�‹
         * @return  {String}    return
         */
        selection: function(mode) {
            var getText = ((mode || 'text').toLowerCase() == 'text');

            try {
                if (win.getSelection) {
                    if (getText) {
                        // get text
                        return win.getSelection().toString();
                    } else {
                        // get html
                        var sel = win.getSelection(), range;

                        if (sel.getRangeAt) {
                            range = sel.getRangeAt(0);
                        } else {
                            range = doc.createRange();
                            range.setStart(sel.anchorNode, sel.anchorOffset);
                            range.setEnd(sel.focusNode, sel.focusOffset);
                        }

                        return $('<div></div>').append(range.cloneContents()).html();
                    }
                } else if (doc.selection) {
                    if (getText) {
                        // get text
                        return doc.selection.createRange().text;
                    } else {
                        // get html
                        return doc.selection.createRange().htmlText;
                    }
                }
            } catch (e) {
                /* ã�‚ã��ã‚‰ã‚�ã‚‹ */
            }

            return '';
        }
    });

    /* selection ã‚’è¿½åŠ  */
    $.fn.extend({
        selection: function(mode, opts) {
            opts = opts || {};

            switch (mode) {
                /**
                 * selection('getPos')
                 * ã‚­ãƒ£ãƒ¬ãƒƒãƒˆä½�ç½®ã‚’å�–å¾—ã�—ã�¾ã�™
                 *
                 * @return  {Object}    return
                 * @return  {Integer}   return.start    é�¸æŠžé–‹å§‹ä½�ç½®
                 * @return  {Integer}   return.end      é�¸æŠžçµ‚äº†ä½�ç½®
                 */
                case 'getPos':
                    return _CaretOperation.getPos(this[0]);
                    break;

                /**
                 * selection('setPos', opts)
                 * ã‚­ãƒ£ãƒ¬ãƒƒãƒˆä½�ç½®ã‚’è¨­å®šã�—ã�¾ã�™
                 *
                 * @param   {Integer}   opts.start      é�¸æŠžé–‹å§‹ä½�ç½®
                 * @param   {Integer}   opts.end        é�¸æŠžçµ‚äº†ä½�ç½®
                 */
                case 'setPos':
                    return this.each(function() {
                        _CaretOperation.setPos(this, opts);
                    });
                    break;

                /**
                 * selection('replace', opts)
                 * é�¸æŠžæ–‡å­—åˆ—ã‚’ç½®ã��æ�›ã�ˆã�¾ã�™
                 *
                 * @param   {String}    opts.text            ç½®ã��æ�›ã�ˆã‚‹æ–‡å­—åˆ—
                 * @param   {String}    opts.caret           ã‚­ãƒ£ãƒ¬ãƒƒãƒˆãƒ¢ãƒ¼ãƒ‰ "keep" | "start" | "end" ã�®ã�„ã�šã‚Œã�‹
                 */
                case 'replace':
                    return this.each(function() {
                        _CaretOperation.replace(this, opts.text, opts.caret);
                    });
                    break;

                /**
                 * selection('insert', opts)
                 * é�¸æŠžæ–‡å­—åˆ—ã�®å‰�ã€�ã‚‚ã�—ã��ã�¯å¾Œã�«æ–‡å­—åˆ—ã‚’æŒ¿å…¥ã�ˆã�¾ã�™
                 *
                 * @param   {String}    opts.text            æŒ¿å…¥æ–‡å­—åˆ—
                 * @param   {String}    opts.caret           ã‚­ãƒ£ãƒ¬ãƒƒãƒˆãƒ¢ãƒ¼ãƒ‰ "keep" | "start" | "end" ã�®ã�„ã�šã‚Œã�‹
                 * @param   {String}    opts.mode            æŒ¿å…¥ãƒ¢ãƒ¼ãƒ‰ "before" | "after" ã�®ã�„ã�šã‚Œã�‹
                 */
                case 'insert':
                    return this.each(function() {
                        if (opts.mode == 'before') {
                            _CaretOperation.insertBefore(this, opts.text, opts.caret);
                        } else {
                            _CaretOperation.insertAfter(this, opts.text, opts.caret);
                        }
                    });

                    break;

                /**
                 * selection('get')
                 * é�¸æŠžã�•ã‚Œã�¦ã�„ã‚‹æ–‡å­—åˆ—ã‚’å�–å¾—
                 *
                 * @return  {String}    return
                 */
                case 'get':
                default:
                    return _CaretOperation.getText(this[0]);
                    break;
            }

            return this;
        }
    });
})(jQuery, window, window.document);
