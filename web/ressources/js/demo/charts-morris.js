$(document).ready(function() {
    var donnees = [
        { y: "Abidjan", a: 2750 },
        { y: "Abengourou", a: 750 },
        { y: "Bouake", a: 1250 },
        { y: "Man", a: 850 },
        { y: "Bondoukou", a: 1050 },
        { y: "Sanpedro", a: 975 },
        { y: "Daloa", a: 723 },
        { y: "Gagnoa", a: 515 },
        { y: "Odienne", a: 122 },
        { y: "Korhogo", a: 167 },
        { y: "Katiola", a: 123 },
        { y: "Yamoussoukro", a: 1075 },
        { y: "Yopougon", a: 2125 },
        { y: "Agboville", a: 698 },
        { y: "Yopougon", a: 2125 }
    ];
    Morris.Bar({
        element: "demo-morris-bar",
        data: donnees,
        xkey: "y",
        ykeys: ["a"],
        labels: ["Cotisants"],
        gridEnabled: !0,
        barColors: ["#29b7d3"],
        resize: !0,
        hideHover: "auto"
    })
});