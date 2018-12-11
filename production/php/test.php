    <script src="../../vendors/Chart.js/dist/raphael-2.1.4.min.js"></script>
    <script src="../../vendors/Chart.js/dist/justgage.js"></script>
      <div class="box">
        <div id="g1" class="gauge"></div>
      </div>
      	<script>
      var g1 = new JustGage({
        id: 'g1',
        value: 10,
        min: 0,
        max: 100,
        symbol: '%',
        pointer: true,
        animattion:false,
        gaugeWidthScale: 1,
        customSectors: [{
          color: '#ff0000',
          lo: 50,
          hi: 100
        }, {
          color: '#00ff00',
          lo: 0,
          hi: 50
        }],
        counter: true
      });
	  
	  </script>