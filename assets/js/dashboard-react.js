const { useState, useEffect } = React;

function Dashboard() {
  const [data, setData] = useState(null);

  useEffect(() => {
    fetch("../backend/api/dashboard-status.php")
      .then(res => res.json())
      .then(json => setData(json));
  }, []);

  useEffect(() => {
    if (!data) return;

    const ctx = document.getElementById("statusChart");

    new Chart(ctx, {
      type: "bar",
      data: {
        labels: ["Food", "Water", "PG"],
        datasets: [{
          label: "Total Reports",
          data: [
            data.food.count,
            data.water.count,
            data.pg.count
          ],
          backgroundColor: ["#22c55e", "#ef4444", "#3b82f6"]
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: {
            labels: { color: "white" }
          }
        },
        scales: {
          y: {
            ticks: { color: "white" }
          },
          x: {
            ticks: { color: "white" }
          }
        }
      }
    });

  }, [data]);

  if (!data) {
    return React.createElement("h3", null, "Loading...");
  }

  return React.createElement(
    "div",
    { className: "dashboard-container" },

    React.createElement("h2", null, "📊 Issue Analytics"),

    React.createElement("canvas", {
      id: "statusChart",
      height: "120"
    })
  );
}

ReactDOM.createRoot(
  document.getElementById("react-dashboard")
).render(
  React.createElement(Dashboard)
);
