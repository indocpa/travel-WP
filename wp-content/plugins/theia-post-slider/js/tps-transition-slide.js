/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */
var tps = tps || {};
tps.transitions = tps.transitions || {};
tps.transitions.slide = function (me, previousIndex, newIndex) {
    var $ = jQuery;

    // Init
    var width = me.slideContainer.innerWidth();
    var diff = newIndex - previousIndex;
    var direction = diff > 0 ? 1 : -1;

    // Start all animations at once, at the end of this function. Otherwise we can get rare race conditions.
    var animationsQueue = [];

    // Remove previous slide.
    var previousSlide = previousIndex !== null ? $(me.slides[previousIndex].content) : null;
    if (previousSlide) {
        me.slideContainer.css('height', previousSlide.innerHeight());
        animationsQueue.push(function () {
            previousSlide
                .css('width', width)
                .css('position', 'absolute')
                .css('left', 0)
                .animate({
                    left: -direction * width
                }, me.options.transitionSpeed, function (me, previousIndex) {
                    return function () {
                        $(this)
                            .detach()
                            .css('position', '')
                            .css('left', '');
                        me.decrementSemaphore();
                    }
                }(me, previousIndex));
        });
    }

    var slide = me.slides[newIndex].content;

    if (previousSlide == null) {
        // Don't animate the first shown slide.
        me.slideContainer.append(slide);
    }
    else {
        slide.css('width', width);
        slide.css('visibility', 'hidden');
        me.slideContainer.append(slide);

        // Call event handlers.
        me.onNewSlide();

        // Animate the height.
        animationsQueue.push(function () {
            me.slideContainer.animate({
                'height': slide.innerHeight()
            }, me.options.transitionSpeed, function (me) {
                return function () {
                    $(this)
                        .css('position', '');
                    me.decrementSemaphore();
                }
            }(me));
        });

        // Animate the new slide.
        animationsQueue.push(function () {
            slide
                .css('left', direction * width)
                .css('position', 'absolute')
                .css('visibility', 'visible')
                .animate({
                    left: '0'
                }, me.options.transitionSpeed, function (me) {
                    return function () {
                        $(this)
                            .css('position', '')
                            .css('width', '');
                        me.slideContainer.css('height', '');
                        me.decrementSemaphore();
                    }
                }(me));
        });
    }

    return animationsQueue;
};