/*
 * jQuery Highlight plugin
 * Copyright (c) 2009 Bartek Szopka, 2012 Natrim
 * Licensed under MIT license.
 */
if (typeof jQuery === "function") jQuery(function($) {
    "use strict";

    /*
     * Based on highlight v3 by Johann Burkard
     * http://johannburkard.de/blog/programming/javascript/highlight-javascript-text-higlighting-jquery-plugin.html
     */

    /*
     * Usage:
     *   // wrap every occurrance of text 'lorem' in content
     *   // with <span class='highlight'> (default options)
     *   $('#content').highlight('lorem');
     *
     *   // search for and highlight more terms at once
     *   // so you can save some time on traversing DOM
     *   $('#content').highlight(['lorem', 'ipsum']);
     *   $('#content').highlight('lorem ipsum');
     *
     *   // search only for entire word 'lorem'
     *   $('#content').highlight('lorem', { wordsOnly: true });
     *
     *   // don't ignore case during search of term 'lorem'
     *   $('#content').highlight('lorem', { caseSensitive: true });
     *
     *   // respect the accents
     *   $('#content').highlight('lorem', { ignoreAccents: false });
     *
     *   // wrap every occurrance of term 'ipsum' in content
     *   // with <em class='important'>
     *   $('#content').highlight('ipsum', { element: 'em', className: 'important' });
     *
     *   // remove default highlight
     *   $('#content').unhighlight();
     *
     *   // remove custom highlight
     *   $('#content').unhighlight({ element: 'em', className: 'important' });
     */

    $.extend({
        highlight: function(node, re, nodeName, className, ignoreAccents) {
            if (node.nodeType === 3) {
                var text = node.data;
                if (ignoreAccents) {
                    text = $.stripAccent(text);
                }
                var match = text.match(re);
                if (match) {
                    var highlight = document.createElement(nodeName || 'span');
                    highlight.className = className || 'highlight';
                    var wordNode = node.splitText(match.index);
                    wordNode.splitText(match[0].length);
                    var wordClone = wordNode.cloneNode(true);
                    highlight.appendChild(wordClone);
                    wordNode.parentNode.replaceChild(highlight, wordNode);
                    return 1; //skip added node in parent
                }
            } else if ((node.nodeType === 1 && node.childNodes) && // only element nodes that have children
            !/(script|style)/i.test(node.tagName) && // ignore script and style nodes
            !(node.tagName === nodeName.toUpperCase() && node.className === className)) { // skip if already highlighted
                for (var i = 0; i < node.childNodes.length; i++) {
                    i += $.highlight(node.childNodes[i], re, nodeName, className, ignoreAccents);
                }
            } else if (node.nodeType === 9) {
                $.highlight(node.body, re, nodeName, className, ignoreAccents);
            }
            return 0;
        },
        stripAccent: function(str) { //find accented characters and replace them with their unaccented version
            var rExps = [/[\xC0-\xC2]/g, /[\xE0-\xE2]/g, /[\xC8-\xCA]/g, /[\xE8-\xEB]/g, /[\xCC-\xCE]/g, /[\xEC-\xEE]/g, /[\xD2-\xD4]/g, /[\xF2-\xF4]/g, /[\xD9-\xDB]/g, /[\xF9-\xFB]/g, /[\u0106\u0108\u010A\u010C]/g, /[\u0107\u0109\u010D\010B]/g, /[\u0154\u0156\u0158]/g, /[\u0155\u0157\u0159]/g, /[\u0179\u017B\u017D]/g, /[\u017A\u017C\u017E]/g, /[\u015A\u015C\u015E\u0160]/g, /[\u015B\u015D\u015F\u0161]/g, /[\u010E\u110]/g, /[\u010F\u0111]/g, /[\u00DD\u0176\u0178]/g, /[\u00FD\u00FF\u0177]/g, /[\u0143\u0145\u0147\u014A]/g, /[\u0144\u0146\u0148\u0149\u014B]/g];

            var repChar = ['A', 'a', 'E', 'e', 'I', 'i', 'O', 'o', 'U', 'u', 'C', 'c', 'R', 'r', 'T', 't', 'Z', 'z', 'S', 's', 'D', 'd', 'Y', 'y', 'N', 'n'];

            for (var i = 0; i < rExps.length; ++i) {
                str = str.replace(rExps[i], repChar[i]);
            }

            return str;
        }
    });

    $.fn.unhighlight = function(options) {
        var settings = {
            className: 'highlight',
            element: 'span'
        };
        $.extend(settings, options);

        // Thank you https://gist.github.com/jonraasch/563055
        function newNormalize(node) {
            var new_node;
            for (var i = 0, children = node.childNodes, nodeCount = children.length; i < nodeCount; i++) {
                var child = children[i];
                if (child.nodeType == 1) {
                    newNormalize(child);
                    continue;
                }
                if (child.nodeType != 3) { continue; }
                var next = child.nextSibling;
                if (next == null || next.nodeType != 3) { continue; }
                var combined_text = child.nodeValue + next.nodeValue;
                new_node = node.ownerDocument.createTextNode(combined_text);
                node.insertBefore(new_node, child);
                node.removeChild(child);
                node.removeChild(next);
                i--;
                nodeCount--;
            }
        }

        return this.find(settings.element + "." + settings.className).each(function() {
            var parent = this.parentNode;
            parent.replaceChild(this.firstChild, this);
            newNormalize(parent);
        }).end();
    };

    $.fn.highlight = function(words, options) {
        var settings = {
            className: 'highlight',
            element: 'span',
            caseSensitive: false,
            wordsOnly: false,
            ignoreAccents: true
        };
        $.extend(settings, options);

        if (words.constructor === String) {
            words = [words];
        }
        words = $.grep(words, function(word, i) {
            return word !== '';
        });
        words = $.map(words, function(word, i) {
            if (settings.ignoreAccents) {
                word = $.stripAccent(word);
            }
            return word.replace(/[-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&");
        });
        if (words.length === 0) {
            return this;
        }

        var flag = settings.caseSensitive ? "" : "i";
        var pattern = "(" + words.join("|") + ")";
        if (settings.wordsOnly) {
            pattern = "\\b" + pattern + "\\b";
        }
        var re = new RegExp(pattern, flag);

        return this.each(function() {
            $.highlight(this, re, settings.element, settings.className, settings.ignoreAccents);
        });
    };
});
