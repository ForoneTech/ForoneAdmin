'use strict';

angular.module('ui.jp', ['oc.lazyLoad', 'ui.load']).
  value('uiJpConfig', {}).
  directive('uiJp', ['uiJpConfig', 'MODULE_CONFIG', 'uiLoad', '$timeout', function uiJpInjectingFunction(uiJpConfig, MODULE_CONFIG, uiLoad, $timeout) {

  return {
    restrict: 'A',
    compile: function uiJpCompilingFunction(tElm, tAttrs) {

      var options = uiJpConfig && uiJpConfig[tAttrs.uiJp];

      return function uiJpLinkingFunction(scope, elm, attrs) {

        function getOptions(){
          var linkOptions = [];

          // If ui-options are passed, merge (or override) them onto global defaults and pass to the jQuery method
          if (attrs.uiOptions) {
            linkOptions = scope.$eval('[' + attrs.uiOptions + ']');
            if (angular.isObject(options) && angular.isObject(linkOptions[0])) {
              linkOptions[0] = angular.extend({}, options, linkOptions[0]);
            }
          } else if (options) {
            linkOptions = [options];
          }
          return linkOptions;
        }

        // If change compatibility is enabled, the form input's "change" event will trigger an "input" event
        if (attrs.ngModel && elm.is('select,input,textarea')) {
          elm.bind('change', function() {
            elm.trigger('input');
          });
        }

        // Call jQuery method and pass relevant options
        function callPlugin() {
          $timeout(function() {
            $(elm)[attrs.uiJp].apply($(elm), getOptions());
          }, 0, false);
        }

        function refresh(){
          // If ui-refresh is used, re-fire the the method upon every change
          if (attrs.uiRefresh) {
            scope.$watch(attrs.uiRefresh, function() {
              callPlugin();
            });
          }
        }

        var jp = false;
        angular.forEach(MODULE_CONFIG, function(module) {
          if( module.name == attrs.uiJp){
            jp = module.files;
          }
        });

        if ( jp ) {
          uiLoad.load(jp).then(function() {
            callPlugin();
            refresh();
          }).catch(function() {

          });
        } else {
          callPlugin();
          refresh();
        }
      };
    }
  };
}]);
