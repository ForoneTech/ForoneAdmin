'use strict';

/**
 * @ngdoc function
 * @name app.directive:uiNav
 * @description
 * # uiScroll
 * Directive of the app
 */
angular.module('app')
  .directive('lazyLoad', ['MODULE_CONFIG','$ocLazyLoad', '$compile', function(MODULE_CONFIG, $ocLazyLoad, $compile) {
    return {
      restrict: 'A',
      compile: function (el, attrs) {
        var contents = el.contents().remove(), name;
        return function(scope, el, attrs){
          angular.forEach(MODULE_CONFIG, function(module) {
            if( module.name == attrs.lazyLoad){
              if(!module.module){
                name = module.files;
              }else{
                name = module.name;
              }
            }
          });
          $ocLazyLoad.load(name)
          .then(function(){
            $compile(contents)(scope, function(clonedElement, scope) {
              el.append(clonedElement);
            });
          });
        }
      }
    };
  }])