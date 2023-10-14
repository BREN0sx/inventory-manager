(function($) {
    $( document ).ready(function() {
        let tableType = localStorage.getItem('tableType');
        
        if (tableType == 'row') {
            document.getElementById('tableContainer').style.display = 'flex';
            document.getElementById('chartContainer').style.display = 'none';

            document.getElementById('products_inrow').style.display = 'none';
            document.getElementById('products_inchart').style.display = 'flex';
        } else {
            document.getElementById('chartContainer').style.display = 'flex';
            document.getElementById('tableContainer').style.display = 'none';

            document.getElementById('products_inrow').style.display = 'flex';
            document.getElementById('products_inchart').style.display = 'none';
        }
    });

    $('#products_inrow').on('click', function(event) {
        localStorage.setItem('tableType', 'row');

        let tableContainer = document.getElementById('tableContainer');
        tableContainer.style.cssText = 'display: flex;'

        let chartContainer = document.getElementById('chartContainer');
        chartContainer.style.cssText = 'display: none;'


        let inChartBtn = document.getElementById('products_inchart');
        inChartBtn.style.cssText = 'display: flex;'

        let inRowBtn = document.getElementById('products_inrow');
        inRowBtn.style.cssText = 'display: none;'
    });
    $('#products_inchart').on('click', function(event) {
        localStorage.setItem('tableType', 'chart');

        let tableContainer = document.getElementById('tableContainer');
        tableContainer.style.cssText = 'display: none;'

        let chartContainer = document.getElementById('chartContainer');
        chartContainer.style.cssText = 'display: flex;'

        let inChartBtn = document.getElementById('products_inchart');
        inChartBtn.style.cssText = 'display: none;'

        let inRowBtn = document.getElementById('products_inrow');
        inRowBtn.style.cssText = 'display: flex;'
    });
    
})(jQuery);