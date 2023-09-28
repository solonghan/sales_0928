"use strict";
$(document).ready(function () {
    window.onload = function () {
        $(function () {
            var inputMapper = {
                "name": 1,
                "phone": 2,
                "office": 3,
                "age": 4
            };
            var dtInstance = $("#table5").DataTable({
                "lengthMenu": [10, 25, 50, "ALL"],
                "responsive": true,
                bLengthChange: false,
                mark: true
            });
            $("input").on("input", function () {
                var $this = $(this);
                var val = $this.val();
                var key = $this.attr("name");
                dtInstance.columns(inputMapper[key] - 1).search(val).draw();
            });
        });
    }
});
