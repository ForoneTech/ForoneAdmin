'use strict';

/**
 * @ngdoc function
 * @name app.directive:uiFullscreen
 * @description
 * # uiFullscreen
 * Directive of the app
 */
angular.module('app')
  .directive('uiFullscreen', ['$ocLazyLoad', '$document', function($ocLazyLoad, $document) {
    return {
      restrict: 'AC',
      link: function(scope, el, attr) {
        el.addClass('hide');
        $ocLazyLoad.load('../libs/jquery/screenfull/dist/screenfull.min.js').then(function(){
          if (screenfull.enabled) {
            el.removeClass('hide');
          } else{
            return;
          }
          el.bind('click', function(){
            var target;
            attr.target && ( target = angular.element(attr.target)[0] );
            screenfull.toggle(target);
          });

          var body = angular.element($document[0].body);
          $document.on(screenfull.raw.fullscreenchange, function () {
            if(screenfull.isFullscreen){
              body.addClass('fullscreen');
            }else{
              body.removeClass('fullscreen');
            }
          });
        });
      }
    };
  }]);
