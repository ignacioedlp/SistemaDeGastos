google.charts.load("current", { packages: ["bar"] });
google.charts.setOnLoadCallback(drawChart);

async function drawChart() {
  const http = await fetch(
    "http://localhost/CursoPHP/ExpesesAPP/expenses/getExpensesJSON"
  )
    .then((json) => json.json())
    .then((res) => res);

  let expenses = [...http];
  expenses.shift();
  console.log(expenses);

  let colors = [...http][0];
  colors.shift();

  var data = google.visualization.arrayToDataTable(expenses);

  var options = {
    colors: colors,
  };

  var chart = new google.charts.Bar(document.getElementById("chart"));

  chart.draw(data, google.charts.Bar.convertOptions(options));
}
