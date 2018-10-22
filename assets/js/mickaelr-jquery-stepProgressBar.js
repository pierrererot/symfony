/*--------------------------------------------------------------------
 *JAVASCRIPT "jquery.stepProgressBar.js"
 *Version:    0.1.5 - 2016
 *author:     Mickaël Roy
 *website:    http://www.mickaelroy.com
 *Licensed MIT
-----------------------------------------------------------------------*/

(function ($){

    var pluginName = 'stepProgressBar';

    /**
     * The plugin constructor
     * @param {DOM Element} element The DOM element where plugin is applied
     * @param {Object} options Options passed to the constructor
     */
    function StepProgressBar(element, options) {
        // Store a reference to the source element
        this.el = element;

        // Store a jQuery reference  to the source element
        this.$el = $(element);

        // Set a random (and normally unique) id for the object
        this.instanceId = Math.round(new Date().getTime() + (Math.random() * 100));

        // Set the instance options extending the plugin defaults and
        // the options passed by the user
        this.settings = $.extend({}, $.fn[pluginName].defaults, options);

        this.stepFields = [
            'value',
            'topLabel',
            'bottomLabel',
            'mouseOver',
            'click',
            'customBottomLabel',
            'rdvLabel'
        ];

        // Initialize the plugin instance
        this.init();
    }

    /**
     * Set up your Plugin protptype with desired methods.
     * It is a good practice to implement 'init' and 'destroy' methods.
     */
    StepProgressBar.prototype = {

        /**
         * Initialize the plugin instance.
         * Set any other attribtes, store any other element reference, register
         * listeners, etc
         *
         * When bind listerners remember to name tag it with your plugin's name.
         * Elements can have more than one listener attached to the same event
         * so you need to tag it to unbind the appropriate listener on destroy:
         *
         * @example
         * this.$someSubElement.on('click.' + pluginName, function() {
         *      // Do something
         * });
         *
         */
        init: function() {
            // init bar structure
            this._buildBar();

            if(this.settings.steps && this.settings.steps.length > 0) {
                // init steps wrapper structure
                this._buildStepsWrapper();
                // update steps / progress / labels values & elements
                this._updateSteps();
            }
        },

        /**
         * getCurrentValue method
         *
         * @example
         * $('#element').pluginName('getCurrentValue');
         */
        getCurrentValue: function() {
            return this.settings.currentValue;
        },

        /**
         * setCurrentValue method
         *
         * @example
         * $('#element').pluginName('setCurrentValue', value);
         *
         * @param  {number} value [the value to set for selector]
         */
        setCurrentValue: function(value) {
            this.settings.currentValue = value;
            this._updateProgress();
            this._findNextStep();
            this._updateNextStepElm();
        },

        /**
         * You can use the name convention functions started with underscore are
         * private. Really calls to functions starting with underscore are
         * filtered, for example:
         *
         *  @example
         *  $('#element').jqueryPlugin('_pseudoPrivateMethod');  // Will not work
         */

        // method that build progress bar HTML structure
        _buildBar: function() {
            this.$el.addClass('step-progressbar-container');
            if(this.settings.rounded)
                this.$el.addClass('step-progressbar-rounded');

            this.topLabelContainer = $('<div>');
            this.topLabelContainer.addClass('step-progressbar-toplabels');
            this.$el.append(this.topLabelContainer);

            this.barContainer = $('<div>');
            this.barContainer.addClass('step-progressbar-bar-wrapper');
            this.$el.append(this.barContainer);
            this.barElm = $('<span>');
            this.barElm.addClass('step-progressbar-bar');
            this.barContainer.append(this.barElm);
            this.progressElm = $('<span>');
            this.progressElm.addClass('step-progressbar-progress');
            this.barElm.append(this.progressElm);

            this.bottomLabelContainer = $('<div>');
            this.bottomLabelContainer.addClass('step-progressbar-bottomlabels');
            this.$el.append(this.bottomLabelContainer);
        },

        // method that build HTML steps wraper structure
        _buildStepsWrapper: function() {
            this.stepsWrapper = $('<span>');
            this.stepsWrapper.addClass('step-progressbar-steps-wrapper');
            this.barElm.append(this.stepsWrapper);
            this.stepsContainer = $('<span>');
            this.stepsContainer.addClass('step-progressbar-steps');
            this.stepsWrapper.append(this.stepsContainer);

            this.toplabelWrapper = $('<span>');
            this.toplabelWrapper.addClass('step-progressbar-labels-wrapper');
            this.topLabelContainer.append(this.toplabelWrapper);
            this.bottomlabelWrapper = $('<span>');
            this.bottomlabelWrapper.addClass('step-progressbar-labels-wrapper');
            this.bottomLabelContainer.append(this.bottomlabelWrapper);
        },

        // method that claculates current progress value (in percent)
        _calcCurrentProgressValue: function() {
            if(isNullOrUndefined(this.settings.steps) || (this.settings.steps && this.settings.steps.length <= 0)) {
                this.progressValue = 100;
            } else {
                var minAndMax = this._retrieveMinAndMaxSteps();
                var minValue = minAndMax.minValue;
                var maxValue = minAndMax.maxValue;

                this.progressValue = getPercentValue(this.settings.currentValue, minValue, maxValue);
            }
            return this.progressValue;
        },

        // method that claculates steps progress value (in percent)
        _calcStepsProgressValues: function() {
            if(isNullOrUndefined(this.settings.steps) || (this.settings.steps && this.settings.steps.length <= 0)) {
                return;
            } else {
                var minAndMax = this._retrieveMinAndMaxSteps();
                var minValue = minAndMax.minValue;
                var maxValue = minAndMax.maxValue;

                for(var i = 0; i < this.settings.steps.length; i++) {
                    var step = this.settings.steps[i];

                    step.progressValue = getPercentValue(step.value, minValue, maxValue);
                }
                return this.settings.steps;
            }
        },

        // method that retreive min & max steps
        _retrieveMinAndMaxSteps: function() {
            if(isNullOrUndefined(this.settings.steps) || (this.settings.steps && this.settings.steps.length <= 0)) {
                return;
            } else {
                var minValue, maxValue, minStep, maxStep;
                for(var i = 0; i < this.settings.steps.length; i++) {
                    if(isNullOrUndefined(minStep)) {
                        minValue = this.settings.steps[i].value;
                        minStep = this.settings.steps[i];
                    }
                    if(isNullOrUndefined(maxStep)) {
                        maxValue = this.settings.steps[i].value;
                        maxStep = this.settings.steps[i];
                    }
                    if(this.settings.steps[i].value < minValue) {
                        minValue = this.settings.steps[i].value;
                        minStep = this.settings.steps[i];
                    }
                    if(this.settings.steps[i].value > maxValue) {
                        maxValue = this.settings.steps[i].value;
                        maxStep = this.settings.steps[i];
                    }
                }
                return {minValue: minValue, minStep: minStep, maxValue: maxValue, maxStep: maxStep};
            }
        },

        // method that finds the step which's following the current value
        _findNextStep: function() {
            if(isNullOrUndefined(this.settings.steps) || (this.settings.steps && this.settings.steps.length <= 0))
                return;
            else if(isNullOrUndefined(this.settings.currentValue)) {
                var minAndMax = this._retrieveMinAndMaxSteps();
                if(minAndMax.maxStep) {
                    this._setNextStep(minAndMax.maxStep);
                    return minAndMax.maxStep;
                } else if(minAndMax.minStep) {
                    this._setNextStep(minAndMax.minStep);
                    return minAndMax.minStep;
                } else
                    return;
            } else {
                var nextStep;
                var idNextStep;
                for(var i = 0; i < this.settings.steps.length; i++) {
                    if(isNullOrUndefined(nextStep) && !isNullOrUndefined(this.settings.steps[i].value) && this.settings.steps[i].value > this.settings.currentValue)
                        nextStep = this.settings.steps[i];
                    else if(!isNullOrUndefined(nextStep) && !isNullOrUndefined(this.settings.steps[i].value) && this.settings.steps[i].value > this.settings.currentValue && this.settings.steps[i].value < nextStep.value)
                        nextStep = this.settings.steps[i];
                }
                if(!isNullOrUndefined(nextStep)){
                    this._setNextStep(nextStep);
                    if(!isNullOrUndefined(this.settings.steps[nextStep.value - 2])){

                        this.settings.steps[nextStep.value - 2].bottomLabelElement.addClass('step-current');
                        this.settings.steps[nextStep.value - 2].stepElement.addClass('step-current');
                        this.settings.steps[nextStep.value - 2].topLabelElement.addClass('step-current');
                    }

                }



                return nextStep;
            }
        },

        // method that sets 'isNextStep' field depending on the provided step parameter
        _setNextStep: function(step) {
            if(isNullOrUndefined(this.settings.steps) || (this.settings.steps && this.settings.steps.length <= 0))
                return;
            for(var i = 0; i < this.settings.steps.length; i++) {
                this.settings.steps[i].isNextStep = false;
            }
            step.isNextStep = true;
        },

        // method that updates steps element in order to distinguish which one is corresponding to the next step
        _updateNextStepElm: function() {
            if(isNullOrUndefined(this.settings.steps) || (this.settings.steps && this.settings.steps.length <= 0))
                return;
            for(var i = 0; i < this.settings.steps.length; i++) {
                if(this.settings.steps[i].isNextStep == true && !isNullOrUndefined(this.settings.steps[i].stepElement)) {
                    this.settings.steps[i].stepElement.addClass('step-progressbar-nextstep');
                    this.settings.steps[i].topLabelElement.addClass('step-progressbar-nextstep');
                    this.settings.steps[i].bottomLabelElement.addClass('step-progressbar-nextstep');
                } else if((this.settings.steps[i].isNextStep == false || isNullOrUndefined(this.settings.steps[i].isNextStep)) && !isNullOrUndefined(this.settings.steps[i].stepElement)) {
                    this.settings.steps[i].stepElement.removeClass('step-progressbar-nextstep');
                    this.settings.steps[i].topLabelElement.removeClass('step-progressbar-nextstep');
                    this.settings.steps[i].bottomLabelElement.removeClass('step-progressbar-nextstep');
                }
            }
        },

        // method that updates
        _updateSteps: function() {
            // sort steps
            this.settings.steps.sort(sortByValue);

            // calculate current progress value depending on steps
            this._updateProgress();

            // calculate steps progress values (in percent)
            this._calcStepsProgressValues();
            // find the step that follows the current progress value
            this._findNextStep();

            for(var i = 0; i < this.settings.steps.length; i++) {
                // for following method calls we suppose that steps are sorted, if it's not always the case we'll have to implementa sort function.

                // create progress bar step element
                this._buildStep(this.settings.steps[i], i);

                // create top label corresponding to that step
                this._buildTopLabel(this.settings.steps[i], i);

                // create bottom label corresponding to that step
                this._buildBottomLabel(this.settings.steps[i], i);
            }

            // update next step html classes
            this._updateNextStepElm();
        },

        // method that updates the bar progress value.
        _updateProgress: function() {
            if(this.progressElm)
                this.progressElm.css('width', this._calcCurrentProgressValue() + '%');
        },

        // method that build a step element. (index is useful to add special classes for 1st and last steps)
        _buildStep: function(step, index) {
            if(isNullOrUndefined(step) || (!isNullOrUndefined(step) && isNullOrUndefined(step.progressValue)))
                return;

            if(isNullOrUndefined(step.stepElement)) {
                var stepElm = $('<span>');
                stepElm.addClass('step-progressbar-step');
                this.stepsContainer.append(stepElm);
                step.stepElement = stepElm;
            }
            // following removeClass are useful just for step update
            step.stepElement.removeClass('step-progressbar-firststep');
            step.stepElement.removeClass('step-progressbar-laststep');
            if(index == 0)
                step.stepElement.addClass('step-progressbar-firststep');
            if(index == this.settings.steps.length - 1)
                step.stepElement.addClass('step-progressbar-laststep');
            step.stepElement.css('left', step.progressValue + '%');
        },

        // method that builds the topLabel element. (index is useful to add special classes for 1st and last steps)
        _buildTopLabel: function(step, index) {
            if(isNullOrUndefined(step) || (!isNullOrUndefined(step) && isNullOrUndefined(step.progressValue)))
                return;

            // initialize element
            if(isNullOrUndefined(step.topLabelElement)) {
                let topLabelElm = $('<span>');
                topLabelElm.addClass('step-progressbar-steplabel');
                this.toplabelWrapper.append(topLabelElm);
                step.topLabelElement = topLabelElm;
            }
            // following removeClass are useful just for step update
            step.topLabelElement.removeClass('step-progressbar-firststep');
            step.topLabelElement.removeClass('step-progressbar-laststep');

            step.topLabelElement.css('left', step.progressValue + '%');

            if(index == 0) {
                step.topLabelElement.addClass('step-progressbar-firststep');
            }
            if(index == this.settings.steps.length - 1){
                step.topLabelElement.addClass('step-progressbar-laststep');
            }
            if(step.topLabel){
                step.topLabelElement.addClass('speech-bubble-top');
            }
            step.topLabelElement.html(step.topLabel);
        },

        // method that builds the bottomLabel element. (index is useful to add special classes for 1st and last steps)
        _buildBottomLabel: function(step, index) {
            if(isNullOrUndefined(step) || (!isNullOrUndefined(step) && isNullOrUndefined(step.progressValue)))
                return;

            if(isNullOrUndefined(step.bottomLabelElement)) {
                var bottomLabelElm = $('<span>');
                bottomLabelElm.addClass('step-progressbar-steplabel');
                if(step.rdvLabel === true){
                    bottomLabelElm.addClass('step-rdv-label');
                }else {

                }
                this.bottomlabelWrapper.append(bottomLabelElm);
                step.bottomLabelElement = bottomLabelElm;
            }
            // following removeClass are useful just for step update
            step.bottomLabelElement.removeClass('step-progressbar-firststep');
            step.bottomLabelElement.removeClass('step-progressbar-laststep');
            step.bottomLabelElement.css('left', step.progressValue + '%');
            if(index == 0){
                step.bottomLabelElement.addClass('step-progressbar-firststep');
            }
            if(index == this.settings.steps.length - 1) {
                step.bottomLabelElement.addClass('step-progressbar-laststep');
            }
            if(step.bottomLabel && step.rdvLabel != true){
                step.bottomLabelElement.addClass('speech-bubble-bottom');
            }
            step.bottomLabelElement.html(step.bottomLabel);
        }
    };

    /**
     * These are real private methods. A plugin instance has access to them
     * @return {[type]}
     */

        //
    var isNullOrUndefined = function(variable) {
            return (variable === null || variable === undefined);
        };

    //
    var getPercentValue = function(value, min, max) {
        if(isNullOrUndefined(value))
            return undefined;
        else {
            if(isNullOrUndefined(min) && isNullOrUndefined(max))
                return 100;
            else if(isNullOrUndefined(min) && !isNullOrUndefined(max)) {
                if(value < max)
                    return 0;
                else
                    return 100;
            } else if(isNullOrUndefined(max) && !isNullOrUndefined(min)) {
                if(value < min)
                    return 0;
                else
                    return 100;
            } else if(!isNullOrUndefined(min) && !isNullOrUndefined(max)) {
                if(value < min)
                    return 0;
                else if(value > max)
                    return 100;
                else
                    return (value - min) / (max - min) * 100;
            }
        }
    };

    // Closure to use to sort array by "value" field
    function sortByValue(a, b) {
        return a.value - b.value;
    };

    $.fn[pluginName] = function(options) {
        var args = arguments;

        if (options === undefined || typeof options === 'object') {
            // Creates a new plugin instance, for each selected element, and
            // stores a reference withint the element's data
            return this.each(function() {
                if (!$.data(this, 'plugin_' + pluginName)) {
                    $.data(this, 'plugin_' + pluginName, new StepProgressBar(this, options));
                }
            });
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {
            // Call a public pluguin method (not starting with an underscore) for each
            // selected element.
            if ($.inArray(options, $.fn[pluginName].getters) !== -1) {
                // If the method allows to work as a getter then break the chainability so we can return a value
                // instead the element reference.
                var instance = $.data(this[0], 'plugin_' + pluginName);
                return instance[options].apply(instance, Array.prototype.slice.call(args, 1));
            } else {
                // Invoke the speficied method on each selected element
                return this.each(function() {
                    var instance = $.data(this, 'plugin_' + pluginName);
                    if (instance instanceof StepProgressBar && typeof instance[options] === 'function') {
                        instance[options].apply(instance, Array.prototype.slice.call(args, 1));
                    }
                });
            }
        }
    };

    /**
     * Names of the pluguin methods that can act as a getter method.
     * @type {Array}
     */
    $.fn[pluginName].getters = ['getCurrentValue', 'findStep'];

    /**
     * Default options
     */
    $.fn[pluginName].defaults = {
        currentValue    : 0, /* current progress value (does not have to be in percent) */
        steps           : [], /* all progression steps */
        rounded         : true, /* to switch between classic/rounded bar */
        responsiveLimit : 800, /* bound value below which the progressbar has to be adapted */
        progressLabel   : function(currentValue, maxValue, minValue, percentValue) {
            return (percentValue + '%');
        }, /* in order to customize label that will be displayed on progress bar. */
        progressFill    : undefined /* if we want to fill progress bar with animated gradient for ex. */
    };

}(jQuery));