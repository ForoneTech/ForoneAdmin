'use strict';

/**
 * @ngdoc function
 * @name app.directive:uiScroll
 * @description
 * # uiScroll
 * Directive of the app
 */
angular.module('app')
  .directive('uiScroll', ['$location', '$anchorScroll', function($location, $anchorScroll) {
    return {
      restrict: 'AC',
      replace: true,
      link: function(scope, el, attr) {
        el.bind('click', function(e) {
          $location.hash(attr.uiScroll);
          $anchorScroll();
        });
      }
    };
  }]);