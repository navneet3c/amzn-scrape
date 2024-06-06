<?php
require "list.php";

$data = [];
foreach ($product_list as $p) {
    $month_dt = [];
    $fp = fopen("public/" . $p["name"] . ".txt", "r") or die("Could not open file!");
    while (($line = fgets($fp)) !== false) {
        $line = trim($line);
        $parts = explode(",", $line);
        
        if (count($parts) < 2) {
            continue;
        }
        
        $time_h = DateTime::createFromFormat("Y-m-d\TH:i:sP", $parts[0]);
        $month_s = $time_h->format("M");
        if (!isset($month_dt[$month_s])) {
            $month_dt[$month_s] = [];
        }
        $month_dt[$month_s][] = [
            "time" => $time_h->format("m-d H:i"),
            "price" => $parts[1],
        ];
    }
    fclose($fp);
    
    $item = [
        "product" => $p["name"],
        "months" => [],
    ];
    foreach ($month_dt as $m => $m_data) {
        $item["months"][] = [
            "month" => $m,
            "color_r" => rand(40, 255),
            "color_g" => rand(40, 255),
            "color_b" => rand(40, 255),
            "data" => $m_data,
        ];
    }
    $data[] = $item;
}

?>
<!-- This is a static file -->
<!-- served from your routes in server.js -->

<!-- You might want to try something fancier: -->
<!-- html/nunjucks docs: http://mozilla.github.io/nunjucks/ -->
<!-- jade: http://jade-lang.com/ -->
<!-- haml: http://haml.info/tutorial.html -->
<!-- hbs(handlebars): http://handlebarsjs.com/expressions.html -->

<!DOCTYPE html>
<html>
  <head>
    <title>Welcome to Glitch!</title>
    <meta name="description" content="A cool thing made with Glitch">
    <link id="favicon" rel="icon" href="https://gomix.com/favicon-app.ico" type="image/x-icon">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/public/style.css">
  </head>
  <body>

    <main>
      <div style="width: 800px;">
<?php foreach($data as $p_i): ?>
        <canvas id="charts_<?= $p_i["product"]; ?>"></canvas>
<?php endforeach; ?>
      </div>
    </main>

    <!-- Your web-app is https, so your scripts need to be too -->
    <script src="https://code.jquery.com/jquery-2.2.1.min.js"
            integrity="sha256-gvQgAFzTH6trSrAWoH1iPo9Xc96QxSZ3feW6kem+O00="
            crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js"></script>
    
    <script>
    $(function() {
      console.log('hello world :o');
<?php foreach($data as $p_i): ?>
      const chart_<?= $p_i["product"]; ?> = new Chart(document.getElementById('charts_<?= $p_i["product"]; ?>'), {
        type: 'line',
        data: {
          datasets: [
<?php foreach($p_i["months"] as $m_i): ?>
          {
            label: '<?= $m_i["month"]; ?>',
            backgroundColor: 'rgb(<?= $m_i["color_r"]; ?>, <?= $m_i["color_g"]; ?>, <?= $m_i["color_b"]; ?>)',
            borderColor: 'rgb(<?= $m_i["color_r"]; ?>, <?= $m_i["color_g"]; ?>, <?= $m_i["color_b"]; ?>)',
            fill: false,
            tension: 0.4,
            data: [
<?php foreach($m_i["data"] as $d_i): ?>
              {
                x: '<?= $d_i["time"]; ?>',
                y: '<?= $d_i["price"]; ?>'
              },
<?php endforeach; ?>
            ],
          },
<?php endforeach; ?>
          ] // datasets
        }, // data
        options: {
          plugins: {
            title: {
              text: '<?= $p_i["product"]; ?> Cost Trend ',
              display: true
            }
          },
          scales: {
            x: {
              title: {
                display: true,
                text: 'Date'
              },
            },
            y: {
              title: {
                display: true,
                text: 'value'
              }
            }
          },
        },
      })
<?php endforeach; ?>
    }) // $()
	  </script>

  </body>
</html>
