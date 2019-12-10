<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>KanoskiBOI</title>
</head>
<body>
	



<div id="kanoski-chart" style="width: 100%;"></div>


<script>

(function(){
	// response.setHeader("Set-Cookie", "HttpOnly;Secure;SameSite=Strict");

	function kanoskiChart(data){
			console.log(data);
		const width = 1200;

		const container = d3.select('#kanoski-chart');
		const svg = container.append('svg')
				.attr("viewBox", [0, 0, width, width])
				.style("font", "inherit")
				.attr('version','1.1')
				.attr('x','0px')
				.attr('y','0px')
				.attr("preserveAspectRatio", "xMidYMid meet")
				.attr('xml:space','preserve')
				.attr('width',width)
				.attr('height',width);

		const g = svg.append("g")
			.attr("transform", `translate(${width / 2},${width / 2})`);

		data.filter(function(d){
			
			return d.Make;
		})



		colors = d3.scaleOrdinal()
			.range(['#17194c','#3e4299','#f57f20','#ef4423'])
			.domain(data.reduce(function(acc,d){
				if(!acc.includes(d.Make)){
					acc.push(d.Make);
				}
				return acc;
			},[]));

		console.log(colors.domain());


		// inner pie make




		// outer pie model
		
		

	}
	document.addEventListener("DOMContentLoaded", function() {
		
		jQuery(document).ready(function($) {

			$.when(
				$.getScript('https://d3js.org/d3.v5.min.js'),
				$.getScript('https://cdnjs.cloudflare.com/ajax/libs/d3-tip/0.9.1/d3-tip.min.js'),
				$.Deferred(function( deferred ){
					$( deferred.resolve );
				})
			)
			.done(function(){

			
				// 	// csvUrl = 'https://www.kanoski.com/wp-content/uploads/2019/12/DeadliestCarstop25.csv';
				csvUrl = 'data.csv';

				d3.csv(csvUrl,function(d){
					return d;
					console.log(d);
				})
				.then(kanoskiChart)


			})
		
		});

	});
	// https://www.kanoski.com/wp-content/uploads/2019/12/DeadliestCarstop25.csv

}())

</script>


<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>



</body>
</html>