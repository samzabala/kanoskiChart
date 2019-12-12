<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>KanoskiBOI</title>
  <link href="https://fonts.googleapis.com/css?family=Khula:400,600,700,800&display=swap" rel="stylesheet">
  <style>

html, body, div, span, applet, object, iframe, picture, source, main, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video {
    margin: 0;
    padding: 0;
    border: 0;
    font-size: 100%;
    font: inherit;
    vertical-align: baseline;
}

body {
    font-family: "Khula", Helvetica, Arial sans-serif;
    color: #0f131c;
    min-width: 320px;
}

  *,
    body {
      font-family: 'Khula', sans-serif;
    }
  </style>
</head>
<body>
  


<!-- KANOSKICHART CODE Copy all code below -->
    <div id="kanoski-chart" style="width: 100%;"></div>

    <script>
    //<![CDATA[
      //wrap in cdata so google doesn't think it's renderblocking 
      (function(){
        const isIE = function(){
          var ua = navigator.userAgent;
          return ua.indexOf("MSIE ") > -1 || ua.indexOf("Trident/") > -1
        }
        //d3 doesnt support older browsers anyway just die
        if(isIE()){
          var error =  document.createElement('div')
          error.className = prefix+'wrapper fatality';
          error.innerHTML = 'Sorry, this graphic needs D3 to render the data but your browser does not support it.\n\n Newer versions of Chrome, Edge, Firefox and Safari are recommended. \n\nSee <em><a target="_blank" rel="nofollow" href="https://d3-wiki.readthedocs.io/zh_CN/master/Home/#browser-platform-support">the official wiki</a></em> for more information';

          document.getElementById('kanoski-chart').appendChild(error);
          
          // break;
          throw new Error('D3 not supported by browser');
        }
        //give the src of csv data here
        const csvUrl = 'https://www.kanoski.com/wp-content/uploads/2019/12/DeadliestCarstop25.csv';
        // const csvUrl = 'data.csv';

        //make the chart here
        function kanoskiChart(data){
          
          const width = 900;

          const radius = [.5,.375,.25];
          const backgroundColor = '#FFF';
          const strokeWidth = 1;

          const fontSize = '10pt';

          const toolTipWidth = 300;
          const toolTipHeight = 120;
          const toolTipTailSize = 40;
          const toolTipTailPyth = Math.sqrt((toolTipTailSize * toolTipTailSize) * 2);

          const numFormat = d3.format(",d");

          //our bois
          var  _ = {};

          //filter to makes so no error occurs if it doesnt exist
          data = data.filter(function(d){
            return d.Make;
          });

          //parse fatalities into numbers
          data.forEach(function(d){
            d.Fatalities = parseFloat(d.Fatalities);
          });

          //sort by make and alphabetically by make
          data = data.sort((a, b) => {
            if( a.Make > b.Make){
              return 1;
            }
            if( a.Make < b.Make){
              return -1;
            };
            return 0;
          });

          //create separate database for separate pie boi
          const dataMake = data.reduce((accumulator, curr)=>{
            if(
              !accumulator.find((make)=>{
                return make.Make === curr.Make	
              })
            ){
              accumulator.push({
                Make: curr.Make,
                Fatalities: curr.Fatalities
              })
            } else {
              const i  = accumulator.findIndex((make)=>make.Make === curr.Make);
              accumulator[i].Fatalities += curr.Fatalities
            }

            return accumulator;
          }, []);
          //container
            const container = d3.select('#kanoski-chart');
            const svg = container.append('svg')
                .attr("viewBox", [0, 0, width, width])
                .style("font", "inherit")
                .style("background-color", backgroundColor)
                .style("max-width", '100%')
                .style("margin", '0 auto')
                .style("display", 'block')
                .attr('version','1.1')
                .attr('x','0px')
                .attr('y','0px')
                .attr("preserveAspectRatio", "xMidYMid meet")
                .attr('xml:space','preserve')
                .attr('width',width)
                .attr('height',width);
          //tooltip
            const tooltip = d3.select("body")
              .append("div")
              .attr('class','tooltip')
              .style("position", "absolute")
              .style("z-index", "10")
              .style("display", "none")
              .style('text-transform','uppercase')
              .style('text-align','center')
              .style('padding','30px')
              .style('margin','0')
              .style('font','inherit')
              .style('box-sizing','border-box')
              .style('font-weight','700')
              .style('border-radius','20px')
              .style('box-shadow','0px 8px 13px 0px rgba(67, 67, 67, 0.27)')
              .style('width',toolTipWidth+'px')
              .style('height',toolTipHeight+'px')
              .style('letter-spacing','.05em')
              .style('background',backgroundColor)
              //tail
                tooltip.append('div')
                  .attr('class','tooltip-tail')
                  .style('width',toolTipTailSize+'px')
                  .style('pointer-events','none')
                  .style('height',toolTipTailSize+'px')
                  .style('position','absolute')
                  .style('bottom',`${toolTipTailSize * -.5}px`)
                  .style('box-shadow',' 8px 8px 8px -8px rgba(67, 67, 67, 0.27)')
                  .style('background',backgroundColor)
                  .style('left','0')
                  .style('right','0')
                  .style('padding','0')
                  .style('border','0')
                  .style('margin','auto')
                  .style('transform',' rotate(45deg)')
              //content appendage
                tooltip.append('div')
                  .style('pointer-events','none')
                  .attr('class','tooltip-content')
                  .style('position','relative')
            //duh
              repositionToolTip = (x,y) => {
                tooltip
                  .style("top", (y - toolTipHeight - (toolTipTailPyth * .5) )+"px")
                  .style("left",(x - (toolTipWidth * .5))+"px");
              }
          //color scale
            const colors = d3.scaleOrdinal()
              .range(['#f49445','#f16038','#ee2b2a','#832339','#4d1e41','#21255c','#3f4494','#737fb9','#a6b9dd'])
              .domain(data.reduce(function(acc,d){
                if(!acc.includes(d.Make)){
                  acc.push(d.Make);
                }
                return acc;
              },[]));
          //get pi path
            getArcPath = (piData,outerRadius,innerRadius,subMethod) => {
              var path = d3.arc()
                .outerRadius( outerRadius )
                .innerRadius( innerRadius );
              return subMethod ? path[subMethod](piData) : path(piData);
            };
          //get data but pi it
            getPiData = (data,index) => {
              var pie =  d3.pie()
                .sort(null)
                .value(function(d,i){
                  return d.Fatalities
                });
                return pie(data)[index];
            }
          //make em
            makeADonut = (key,dataToUse,outerRadiusIndex,innerRadiusIndex) => {
              //group
                _['g'+key] = svg.append('g')
                  .attr('transform', `translate(${width / 2},${width / 2})`);
              //graph item
                _['blob'+key] = _['g'+key].append('g')
                  .selectAll('path')
                  .data(dataToUse,d => d[key])
                  .join('path')
                  .attr('data-make', d=> d.Make)
                  .attr('data-model', d=> d.Model)
                  .attr('fill', d => colors(d.Make))
                  .attr('stroke-width',strokeWidth)
                  .attr('stroke',backgroundColor)
                  .attr('d', (d,i) => getArcPath( getPiData(dataToUse,i),( width * radius[outerRadiusIndex]),( width * radius[innerRadiusIndex]) ))
                  .on('mouseenter',function(d){
                    tooltip
                      .style("display", "block")
                      .select('.tooltip-content')
                        .html(function(){
                          //tooltip content
                          var html =  `<p style="
                            font-size:18px;
                            line-height:35px;
                            color:${colors(d.Make)};
                          ">${ d[key] } ${ (key == 'Make') ? 'overall' : '' }</p>`;
                          html += `<p style="
                            font-size:30px;
                            line-height:35px;
                            color:${colors(d.Make)};
                          ">${d.Fatalities}</p>`
                          return html;
                        });
                    repositionToolTip(event.pageX,event.pageY);
                    console.log('enter');
                  })
                  .on("mousemove", function(){
                    console.log('move');
                    repositionToolTip(event.pageX,event.pageY);
                  })
                  .on('mouseleave', function(d){
                    console.log('leave');
                    tooltip
                      .style("display", "none")
                      .select('.tooltip-content')
                      .html('')
                  });
              //text 
                _['text'+key] = _['g'+key].append("g")
                  .style("user-select", "none")
                  .style("font", "inherit")
                  .style("pointer-events", "none")
                .selectAll("text")
                  .data(dataToUse,d => d[key])
                  .join("text")
                  .attr("text-anchor", "middle")
                  .attr('dominant-baseline','middle')
                  .attr('fill',backgroundColor)
                  .attr('font-size',fontSize)
                  .attr('font-weight', key == 'Make' ? '900' : 'inherit' )
                  .attr('transform-origin','0 0')
                  .attr('transform',(d,i)=> {
                    var textOrigin = getArcPath(
                      getPiData(dataToUse,i),
                      ( width * radius[outerRadiusIndex]),
                      ( width * radius[innerRadiusIndex]),
                      'centroid'
                    );
                    var angle = (getPiData(dataToUse,i).startAngle + getPiData(dataToUse,i).endAngle) * 90 / Math.PI - 90;
                    var transform = '';
                    transform = `translate(${textOrigin[0]},${textOrigin[1]})`;
                    transform += `rotate(${angle > 90 ? angle - 180 : angle})`;
                    return transform;
                  })
                  .html(d => {
                    // return '<tspan dy="1em">'+d[key].split(' ').join('</tspan><tspan>')+'</tspan>'
                    return d[key];
                  });
            }
          //yes
            makeADonut('Make',dataMake,1,2);
            makeADonut('Model',data,0,1);
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
              d3.csv(csvUrl,function(d){
                return d;
                console.log(d);
              })
              .then(kanoskiChart)
            })
          });
        });
      }())

    //]]>
    </script>
<!-- END OF COPYABLE CODE -->


<script
  src="https://code.jquery.com/jquery-3.4.1.min.js"
  integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo="
  crossorigin="anonymous"></script>



</body>
</html>