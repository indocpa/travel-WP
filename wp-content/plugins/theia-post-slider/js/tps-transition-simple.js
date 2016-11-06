/*
 * Copyright 2012-2015, Theia Post Slider, WeCodePixels, http://wecodepixels.com
 */
var tps = tps || {};
tps.transitions = tps.transitions || {};
tps.transitions.simple = function (me, previousIndex, index) {
    var $ = jQuery;

    // Init
    var width = me.slideContainer.innerWidth();

    // Start all animations at once, at the end of this function. Otherwise we can get rare race conditions.
    var animationsQueue = [];

    // Remove previous slide
    var previousSlide = previousIndex !== null ? $(me.slides[previousIndex].content) : null;
    if (previousSlide) {
        me.slideContainer.css('height', previousSlide.innerHeight());
        previousSlide.detach();
    }

    // Set the current slide
    var slide = $(me.slides[index].content);

    if (previousSlide == null) {
        // Don't animate the first shown slide
        me.slideContainer.append(slide);
    }
    else {
        me.slideContainer.append(slide);

        // Call event handlers
        me.onNewSlide();

        // Animate the height
        animationsQueue.push(function () {
            me.slideContainer.animate({
                'height': slide.innerHeight()
            }, me.options.transitionSpeed, function (me, previousIndex) {
                return function () {
                    $(this)
                        .css('position', '')
                        .css('width', '');
                    me.slideContainer.css('height', '');
                    me.decrementSemaphore();
                }
            }(me, previousIndex));
        });
    }

    return animationsQueue;
};