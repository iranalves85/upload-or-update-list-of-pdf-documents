$app = angular.module('updateDoc', []);
$app.controller("updateDocController", ['$scope', '$http', function updateDocController($scope, $http) {

    //Qtds
    $scope.fundos = []; //Query de páginas que possuem CP 'uulpd_page=true'    

    $http.post('/', {
        'action': 'uulpd_query_pages'
    }, ).then(
        function() {
            echo "show";
        },
        function() {
            echo "error";
        });

}]);