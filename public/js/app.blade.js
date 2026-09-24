// app.blade.js — reszta (dark mode, przycisk "do góry", podgląd wideo na hover)
// przeniesiona do marquee-preview.js, żeby uniknąć dwóch niezależnych
// systemów nasłuchujących na te same zdarzenia. Ten plik zostaje tylko
// dla inicjalizacji tooltipów Bootstrapa używanych w panelu przesyłania
// i w adminie.
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});
