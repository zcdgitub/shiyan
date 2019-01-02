var labelType, useGradients, nativeTextSupport, animate;

(function() {
  var ua = navigator.userAgent,
      iStuff = ua.match(/iPhone/i) || ua.match(/iPad/i),
      typeOfCanvas = typeof HTMLCanvasElement,
      nativeCanvasSupport = (typeOfCanvas == 'object' || typeOfCanvas == 'function'),
      textSupport = nativeCanvasSupport 
        && (typeof document.createElement('canvas').getContext('2d').fillText == 'function');
  //I'm setting this based on the fact that ExCanvas provides text support for IE
  //and that as of today iPhone/iPad current text support is lame
  labelType = (!nativeCanvasSupport || (textSupport && !iStuff))? 'Native' : 'HTML';
  nativeTextSupport = labelType == 'Native';
  useGradients = nativeCanvasSupport;
  animate = !(iStuff || !nativeCanvasSupport);
})();

var Log = {
  elem: false,
  write: function(text){
    if (!this.elem) 
      this.elem = document.getElementById('log');
    this.elem.innerHTML = text;
    //this.elem.style.left = (500 - this.elem.offsetWidth / 2) + 'px';
  }
};

function treemap(orientation,levels,datatype)
{
   
    //Implement a node rendering function called 'nodeline' that plots a straight line
    //when contracting or expanding a subtree.
	//添加一个nodeline类型的node,在node的type属性中可以指定该node类型
    $jit.ST.Plot.NodeTypes.implement({
        'nodeline': {
          'render': function(node, canvas, animating) {
                if(animating === 'expand' || animating === 'contract') {
                  var pos = node.pos.getc(true), nconfig = this.node, data = node.data;
                  var width  = nconfig.width, height = nconfig.height;
                  var algnPos = this.getAlignedPos(pos, width, height);
                  var ctx = canvas.getCtx(), ort = this.config.orientation;
                  ctx.beginPath();
                  if(ort == 'left' || ort == 'right') {
                      ctx.moveTo(algnPos.x, algnPos.y + height / 2);
                      ctx.lineTo(algnPos.x + width, algnPos.y + height / 2);
                  } else {
                      ctx.moveTo(algnPos.x + width / 2, algnPos.y);
                      ctx.lineTo(algnPos.x + width / 2, algnPos.y + height);
                  }
                  ctx.stroke();
              } 
          }
        }
          
    });
    $jit.ST.Plot.EdgeTypes.implement({
      'text-bezier': {
       'render': function(adj, canvas) {
         var orn = this.getOrientation(adj),
             nodeFrom = adj.nodeFrom, 
             nodeTo = adj.nodeTo,
             rel = nodeFrom._depth < nodeTo._depth,
             begin = this.viz.geom.getEdge(rel? nodeFrom:nodeTo, 'begin', orn),
             end =  this.viz.geom.getEdge(rel? nodeTo:nodeFrom, 'end', orn),
             dim = adj.getData('dim'),
             ctx = canvas.getCtx();
         ctx.beginPath();
         ctx.moveTo(begin.x, begin.y);
         switch(orn) {
           case "left":
             ctx.bezierCurveTo(begin.x + dim, begin.y, end.x - dim, end.y, end.x, end.y);
			 textBaseline="middle";
			 textAlign="right";
             break;
           case "right":
             ctx.bezierCurveTo(begin.x - dim, begin.y, end.x + dim, end.y, end.x, end.y);
			 textBaseline="middle";
			 textAlign="left";
             break;
           case "top":
             ctx.bezierCurveTo(begin.x, begin.y + dim, end.x, end.y - dim, end.x, end.y);
			 textBaseline="bottom";
			 textAlign="center";
             break;
           case "bottom":
             ctx.bezierCurveTo(begin.x, begin.y - dim, end.x, end.y + dim, end.x, end.y);
			 textBaseline="top";
			 textAlign="center";			 
             break;
         }
         ctx.stroke();
           /*
		 ctx.save()
		 ctx.font="20px Arial";
		 ctx.fillStyle="green";
		 ctx.textAlign=textAlign;
		 ctx.textBaseline=textBaseline;
		 user_order=adj.nodeTo.getData('user_order');
		 ctx.fillText(user_order,end.x,end.y);
		 ctx.restore()*/
       }
	  }
    });
    //init Spacetree
    //Create a new ST instance
    switch(orientation)
    {
        case "top":
            myoffsetx=0;
            myoffsety=150;
            break;
        case "bottom":
            myoffsetx=0;
            myoffsety=-150;
            break;
        case "left":
            myoffsetx=200;
            myoffsety=0;
            break;
        case "right":
            myoffsetx=-200;
            myoffsety=0;
            break;
    }
    var st = new $jit.ST({
        'injectInto': 'infovis',
        //set duration for the animation
        duration: 400,
        //set animation transition type
        transition: $jit.Trans.Quart.easeInOut,
        //set distance between node and its children
        levelDistance: 40,
        //set max levels to show. Useful when used with
        //the request method for requesting trees of specific depth
        levelsToShow: levels-1,
		orientation: orientation,
		offsetY:myoffsety,
        offsetX:myoffsetx,
        //是否收缩
		constrained:true,
		//两个分支下节点(左分支的右边节点，右分支的左边节点)的最近距离
		subtreeOffset:5,
		//同一分支下两个节点的最近距离
		siblingOffset:3,
        //set node and edge styles
        //set overridable=true for styling individual
        //nodes or edges
        Node: {
            height: 100,
            width: 120,
            //use a custom
            //node rendering function
            type: 'rectangle',
            color:'#23A4FF',
            lineWidth: 2,
            align:"center",
            overridable: true
        },
        Edge: {
            type: 'text-bezier',
            lineWidth: 2,
            color:'#23A4FF',
            overridable: true
        },
	  Label: {  
			textAlign:"center"
	  } ,
	  Tips: {  
		enable: true,  
		type: 'auto',  
		offsetX: 10,  
		offsetY: 10,
		onShow: function(tip, node) {
			tip.innerHTML = node.data.tip;
		}
	  },
	Navigation: {  
		enable: true,  
		panning: 'avoid nodes',  
		zooming: 20  
	  },
        //Add a request method for requesting on-demand json trees. 
        //This method gets called when a node
        //is clicked and its subtree has a smaller depth
        //than the one specified by the levelsToShow parameter.
        //In that case a subtree is requested and is added to the dataset.
        //This method is asynchronous, so you can make an Ajax request for that
        //subtree and then handle it to the onComplete callback.
        //Here we just use a client-side tree generator (the getTree function).
        request: function(nodeId, level, onComplete)
        {
            id=nodeId.substr(4);
		    $.getJSON("orgMapJson",{"id":id,"levels":levels,"dataType":datatype,"type":"jit"},function(data){
			onComplete.onComplete(nodeId, data); 
		  });
        },

        onBeforeCompute: function(node){
            Log.write("加载" + node.name);
        },
        
        onAfterCompute: function(){
            Log.write("加载完成");
        },
        
        //This method is called on DOM label creation.
        //Use this method to add event handlers and styles to
        //your node.
        onCreateLabel: function(label, node){
            label.id = node.id;            
            label.innerHTML = decodeURI(node.data.info);
/*            label.onclick = function(id) {
                id = node.id.substr(4);
                if ('rectangle' == node.data.$type) {
                    $.getJSON("orgMapJson", {
                        "id": id,
                        "levels": levels,
                        "dataType": datatype,
                        "type": "jit"
                    }, function (data) {
                        st.loadJSON(data);
                        st.compute();
                        st.onClick(st.root);
                    });
                }
            };*/
            label.onclick = function(){
                st.onClick(node.id);
            };
            //set label styles
            var style = label.style;
            style.width = 120 + 'px';
            style.height = 100 + 'px';
            style.cursor = 'pointer';
            style.color = 'black';
            //style.backgroundColor = '#1a1a1a';
            style.fontSize = '12px';
            style.textAlign= 'center';
            //style.textDecoration = 'underline';
            style.paddingTop = '3px';
        },
        
        //This method is called right before plotting
        //a node. It's useful for changing an individual node
        //style properties before plotting it.
        //The data properties prefixed with a dollar
        //sign will override the global node style properties.
		//设置中心节点的颜色与其它节点不同
		/*
        onBeforePlotNode: function(node){
            //add some color to the nodes in the path between the
            //root node and the selected node.
            if (node.selected) {
                node.data.$color = "#ff7";
            }
            else {
                delete node.data.$color;
            }
        },
        */
        //This method is called right before plotting
        //an edge. It's useful for changing an individual edge
        //style properties before plotting it.
        //Edge data proprties prefixed with a dollar sign will
        //override the Edge global style properties.
        onBeforePlotLine: function(adj){
            if (adj.nodeFrom.selected && adj.nodeTo.selected) {
                adj.data.$color = "#FFBD23";
                adj.data.$lineWidth = 3;
            }
            else {
                delete adj.data.$color;
                delete adj.data.$lineWidth;
            }
        }
    });
    //load json data
    st.loadJSON(json);
    //compute node positions and layout
    st.compute();
    //emulate a click on the root node.
    st.onClick(st.root);
    //end
    //Add event handlers to switch spacetree orientation.
}
