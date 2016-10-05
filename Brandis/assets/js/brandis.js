$(document).ready(function() {

    
 if (typeof (App) != 'undefined') {
            App.tags = categories_json;
            var markers = {};
    
            App.filterControl;
            App.menuControl;
  
           App.mapInit = function(){
       
               //layers
               var googleSatLayer = L.tileLayer('http://{s}.google.com/vt/lyrs=s&x={x}&y={y}&z={z}',{
                   maxZoom: 20, //20
                   subdomains:['mt0','mt1','mt2','mt3']
               });

              var stamenlayer = new L.StamenTileLayer("toner"); //terrain

              App.map = new L.Map("map", {
                  center: new L.LatLng(51.3329895, 12.608),
                  zoomControl: false,
                  maxZoom: 16,
                  zoom: 13
              });
              App.map.addLayer(stamenlayer);
      
              App.clickMarker = function(id){
	                 //hier dann pop Up material
	                 App.showContent(id);
	                 console.log("funzt -> bin ID:"+id)
	                 //und trigger timeline
	                 App.timeline.goToId(id);
	            }
	            
	            
        	    var geoJsonLayer = L.geoJson(geoJsonData, {
            		    pointToLayer: function (feature, latlng) {
            		        var markerOptions ={
            		            dataId: feature.id,
            		          //  title: feature.properties.categories.join(", "),
            		            className: "mySpecialClass",
            		          //  tags: feature.properties.categories,
                              radius: 8,
                              fillColor: "#00b9cb", //"#ff7800",
                              color: "#00b9cb", //"#ff7800",
                              weight: 1,

                              opacity: 0.4,
                              fillOpacity: 0.8
            		        }

            		            markers[feature.id] =  L.circleMarker(latlng, markerOptions);
            		          //   markers[feature.id] =  L.circleMarker(latlng, markerOptions).on('click', function(){
            		          //       
            		          //       App.clickMarker(feature.id);
            		          //  
            		          //  });
            		             
            		          //Add id to marker
            		            markers[feature.id].id = feature.id;
		           // console.log("markers[feature.id].id:::"+markers[feature.id].id);
                            return markers[feature.id];
                    },
            			onEachFeature: function (feature, layer) {
			    
		     
            			  //  var catStr = feature.properties.categories.join(", ");
        			      //
        			    //var html = feature.content;
             		    //   layer.bindPopup(feature.id);
 		    
 		    
 		   
            			}
            		});
    		
            		//marker_layer = new L.featureGroup();
            		marker_layer =  L.markerClusterGroup({
            		    showCoverageOnHover: false,
            		   
            		    spiderfyDistanceMultiplier: 1.8,
            	        spiderLegPolylineOptions:{ weight: 2, color: '#00b9cb', opacity: 0.5 },
            		 //   maxClusterRadius: function (zoom) {
                     //           return (zoom <= 14) ? 80 : 1; // radius in pixels
                     //       }
            		});
                      for(i in markers ){
                        markers[i].on('click', function(){
                            console.log("click--"+this.id);
      		                 App.clickMarker(this.id);
      		            });
      		            
                          marker_layer.addLayer(markers[i]);
                          
                       }
                       
                       marker_layer.addTo(App.map);
                       
                      
                      
                       //   marker_layer.on("add", function (event) {
                       //       var clickedMarker = event.layer;
                       //       console.log("clickedMarker:"+clickedMarker.id);
                       //   });

                      
                   	   App.map.fitBounds(marker_layer.getBounds());
           	   
                   	   ///////************************************************/

                          //controls
                          //L.Control.Filters = L.Control.extend({
                            App.filterControl = L.Control.extend({
                              options: {
                                  position: 'bottomright'
                              },
                              onAdd: function (map) {
                          
                        
                                 var container = L.DomUtil.create('div', 'menu-container '); //<i class="fa fa-times" aria-hidden="true"></i>
                               
                                var inside = '<div class="clearfix"><a href="#" class="closeMenuButton pull-right "><i class="mdi mdi-close icon"></i></a></div> <ul id="categorylist-map">';
                                 inside +='<li><a class="" id ="cat_neu" title="Neueste Beiträge">Neu</a></li>';
                                 for(i in App.tags ){
                                     if(i < 10)
                                        inside +='<li><a data-index="'+App.tags[i].idx+'"  data-termid="'+App.tags[i].term_id+'" title="'+App.tags[i].description+'">'+App.tags[i].label+'</a></li>';
                                 }   
                                 inside +='<li id="search-li"><input type="text"  id="search-category"><input  type="hidden" id="search-category-id"></li>';
                                 inside +='</ul>';
                                 container.innerHTML = inside;
                                    
                                 //App.initCloseHandler();
                         
                                 return container;
                              }
                          });
                  
                         // L.control.filters = function (options) {
                         //     return new L.Control.Filters(options);
                         // }; 
                          //App.map.addControl(L.control.filters());
                  
                          //menu open Button
                          //L.Control.menu = L.Control.extend({
                           App.menuControl = L.Control.extend({
                                options: {
                                    position: 'bottomright',

                                },onAdd: function (map) {

                                   var container = L.DomUtil.create('div', 'menu-open leaflet-bar'); //<i class="fa fa-bars" aria-hidden="true"></i>
                                   var inside = '<a href="#"><i class="mdi mdi-tag-multiple icon"></i></a>';
                                   container.innerHTML = inside;
                                   return container;
                                }
                            });
                             //zoom
                              new L.Control.Zoom({ position: 'bottomright' }).addTo(App.map);
                              $('.leaflet-control-zoom-in').html("<i class='mdi mdi-plus icon'></i>");
                              $('.leaflet-control-zoom-out').html("<i class='mdi mdi-minus icon'></i>");
                              //Menucontrol
                            App.this_menuControl = App.map.addControl(new App.menuControl());
                            //App.map.addControl(new L.Control.menu());
                            // Filter Control
                            App.map.addControl(new App.filterControl());
                              App.initCloseHandler();
                              App.initAutocomplete();
                    
                 
               
            }
            App.initCloseHandler = function(){

                $('.closeMenuButton').click(function(e){
                         e.preventDefault();
                         $('.menu-container').hide();
                         $('.menu-open').show();
                  
                    });
                   
            }
            
            
            
            var filterIndex = 10;
            App.initAutocomplete = function(){
                 
                     $( "#search-category" ).autocomplete({
                       minLength: 0,
                       source: App.tags,
                       focus: function( event, ui ) {
                         $( "#search-category" ).val( ui.item.label );
                         return false;
                       },
                       change: function( event, ui ) {
                           console.log("change");
                       },
                       select: function( event, ui ) {
                         //$( "#search-category" ).val( ui.item.label );
                         $( "#search-category-id" ).val( ui.item.term_id );
                        
                         var selected = ui.item.term_id ;
                         if($.inArray( selected, categories ) === -1){
                      		        categories.push(selected);
                      	 }

                  	    searchSpotsForCategory();
                  	    if(ui.item.idx <= 9){
                  	      //highlight existing
                  	      $("a[data-termid=" + selected + "] ").addClass('filter-active');
                  	    }else{  
                  	        
                  	    var html = '<li><a  class="filter-active" data-index="'+filterIndex+'" data-termid="'+selected+'" title="'+ui.item.description+'">'+ui.item.label+'</a></li>';
                  	        $( html ).insertBefore( "#search-li" );
                      	    filterIndex++;
                      	    App.initFilter();
                  	    }
                        $( "#search-category" ).val('');
                         return false;
                       }
                     })
                     .autocomplete( "instance" )._renderItem = function( ul, item ) {
                         var t = item.label.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(this.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>");
                           
                             return $('<li></li>')
                             		.data('item.autocomplete', item)
                             		.append('<a> ' + t + '</a>')
                             		.appendTo(ul);
                     };
                     
                     $( "#search-category" ).click(function(){
                         $(this).focus();
                     });
               
            }
    
            App.mapInit();     
             
            App.initCloseCampaign = function(){

                 $('.close_campaign').click(function(e){
                        
                        e.preventDefault();
                        $('#campaign').html('');
                        $('#campaign').hide();
                    });
            }
            App.initCloseCampaign();
            
            App.closeCampaign = function(){
                $('#campaign').html('');
                $('#campaign').hide();
            }
    
    
            $('.menu-open > a').click(function(e){
                e.preventDefault();
               $('.menu-open').hide();
               $('.menu-container').show();
    
               });

     
           App.drawMarkers = function (postIds){

               //erst aufräumen
               if (App.map.hasLayer(marker_layer)) {
                   App.map.removeLayer(marker_layer);
               }
               //dann neu zeichnen
              // marker_layer = new L.featureGroup();
              marker_layer =  L.markerClusterGroup({
      		    showCoverageOnHover: false
      		});
               // filtern
               for(i in geoJsonData[0].features ){
            
                   for(j in postIds ){
                       var markerID = geoJsonData[0].features[i].id;
                       
                       if(markerID == postIds[j]){

                   		        marker_layer.addLayer(markers[markerID]);
                       }
                      
        		        }//postids loop
		        
        	    }//markers loop
               marker_layer.addTo(App.map);
               
              if(marker_layer.getLayers().length > 1){
                  App.map.fitBounds(marker_layer.getBounds());
              }else if(marker_layer.getLayers().length == 1){
                   var m =  marker_layer.getLayers();
                   App.map.setView(m[0].getLatLng(), 17);        
              }
       
           }
   
            App.showContent = function (postID){
        
                  var o = {};
                  o.postID = postID;
                  o.action = 'getPostContent';

                 $.post(my_ajax_object.ajax_url, o, function(response) {
                     if(response.error == "N"){
                    
                
                        var container = '<div id="contentOverlay"></div>';
                
                    	if ($("#contentOverlay").size() == 0) {
                			$("body").append(container);
                		}else{
                		    //$("#contentOverlay").html('');
                		    $("#contentOverlay").remove()
            		    }
                        var html = '<div class="close_overlay"><a href="#" ><i class="mdi mdi-close icon"></i></a></div>';
                            html += '<h2>'+response.post_title+'</h2>';
                            if(response.video)
                                html += '<div class="videoWrapper">'+response.video+'</div>';
                            if(response.audio){
                                html += '<div class="audioWrapper">';
                                if(response.audio_bild)
                                    html += '<div class="audio_img">'+response.audio_bild+'</div>';
                                html += response.audio+'</div>';
                            }
                            if(response.bilder){
                                html += '<div class="sliderWrapper">';
                                $.each(response.bilder, function( i, obj ) {
                                    html += '<div class="slick-container">';
                                    html += '<img src="'+obj.bild+'" alt="'+obj.alt+'">';
                                    html += '<div class="caption">'+obj.caption+'</div>';
                                    html += '<div class="caption">Quelle: <i>'+obj.quelle+'</i></div>';
                                    html += '</div>';
                                  
                                   
                                });
                                
                                html += '</div>';
                            }
                            html += '<div class="content">'+response.post_content+'</div>';
                        $("#contentOverlay").append(html);
                        //initSlider
                       
                        if($('.sliderWrapper').length){
                            $(".sliderWrapper").not('.slick-initialized').slick({
                              //$('.sliderWrapper').slick({
                                  dots: true,
                                  speed: 500
                                });
                            }
                
                        //nun closebutton init
                        $('.close_overlay').click(function(e){
                            e.preventDefault();
                            //$("#contentOverlay").html('');
                            $("#contentOverlay").remove();
                        });
                        //clean checked categories
                        //App.cleanUpCategories();

                       }else{
                           console.log("Fehler::"+response.msg);
                       }


            	   }, 'json');
            }
   
   
   
 
      
           //add click function to categories *****************************************************************************/
           var categories = [];
           $("#category-form input[type=checkbox]").click(function(){
          
               if($(this).attr('id') == 'cat_neu'){
                   
                   App.getNewest();
               }else{
                   if($(this).prop('checked') == true){
                       if($.inArray( $(this).val(), categories ) === -1){
                		        categories.push($(this).val());
                	   }
                	}else{
                	    var index = $.inArray( $(this).val(), categories );
                	    categories.splice(index, 1);
            	    }
            	    console.log("category::"+$(this).val()+"---categories::"+categories.join(", "));
            	   if(categories.length > 0){
            	        searchSpotsForCategory();
            	    }else{
            	        App.resetMarkers(); 
            	        //cleanup campaign
            	        App.closeCampaign();
            	        
                    }
                }
	        
           });
           
           App.initFilter = function(){
               $("#categorylist-map li a").unbind( "click" );
               $("#categorylist-map li a").bind( "click", function() {

                      if($(this).attr('id') == 'cat_neu'){
                        if($(this).hasClass('filter-active')){
                            
                            $( this ).removeClass('filter-active'); 
                            App.resetMarkers(); 
                        }else{
                            
                            $('.filter-active').each(function(i, obj) {
                                $(obj).removeClass('filter-active'); 
                            });
                            categories = [];
                            
                            $(this).addClass('filter-active');
                            App.getNewest();
                        }
                      }else{
                          
                        if($('#cat_neu').hasClass('filter-active'))
                            $('#cat_neu').removeClass('filter-active'); 
                          var termid = $(this).data('termid');

                          if($(this).hasClass('filter-active')){
                          
                              var index = $.inArray( termid, categories );
                         	  categories.splice(index, 1);
                              $( this ).removeClass('filter-active'); 
                              if($(this).data('index') > 9){
                                  $(this).remove();
                                  filterIndex--;
                               }
                   	   
                       	}else{
                   	    
                            if($.inArray( termid, categories ) === -1){
                   		        categories.push(termid);
                   	        }
                   	        $( this ).addClass('filter-active');
                   	     }   
                   	    console.log("category - init Filter::"+termid+"---categories::"+categories.join(", "));
               	    
                   	   if(categories.length > 0){
                   	        searchSpotsForCategory();
                   	    }else{
                   	        App.resetMarkers(); 
                   	        //cleanup campaign
                   	        App.closeCampaign();
                           }
                       }

                  });
              }
              App.initFilter();
  
          App.resetMarkers = function(){
              var postIds = [];
              for(i in markers){
                  postIds.push(markers[i].id);
              }
              App.drawMarkers(postIds);
              App.timeline._timenav.highlight([]);
          }
  
          App.cleanUpCategories = function(){
               $("#category-form input[type=checkbox]").each(function () {
                      if (this.checked) {
                          $(this).attr('checked', false);
                      }
                      //close menu
                      $('.menu-container').hide();
                       $('.menu-open').show();
               });
            }
    
          //
         App.map.on('click', function(e) {   
                 $("#contentOverlay").remove(); 
                 App.closeCampaign();
        });
         
            ///////************************************************/
     
       
          /****************************************************************** custom functions ****/
          
          App.getNewest = function(){
              var o = {};
          
                o.action = 'getNewest';

               $.post(my_ajax_object.ajax_url, o, function(response) {
                   if(response.error == "N"){
                          App.drawMarkers(response.postIDs);
                          //highlight timeline markers
                          App.timeline._timenav.highlight(response.postIDs);
                          

                     }else{
                         console.log("Fehler::"+response.msg);
                     }


          	   }, 'json');
          }
          
        
            
          function searchSpotsForCategory(){
              console.log("categoris to search for :"+categories.join(", "));

              var o = {};
              o.categories = categories;
              o.action = 'getIdsByCategories';
             
             $.post(my_ajax_object.ajax_url, o, function(response) {
                 if(response.error == "N"){
                        App.drawMarkers(response.postIDs);
                        //highlight timeline markers
                        App.timeline._timenav.highlight(response.postIDs);
                        //if then show Campaign
                        if (typeof (response.campaign_title) != 'undefined') {
                            var html = ' <div class="close_campaign"><a href="#" ><i class="mdi mdi-close icon"></i></a></div>';
                                html += '<h2>'+response.campaign_title+'</h2>';
                                html += '<p>'+response.campaign_txt+'</p>';
                            $('#campaign').html(html);
                            $('#campaign').show();
                            //nun closebutton init
                            App.initCloseCampaign();
                            
                        }else{
                            App.closeCampaign();
                        }

                   }else{
                    
                       App.drawMarkers([]);
                       App.timeline._timenav.highlight([]);
                       console.log("Fehler::"+response.msg);
                   }
	   
	   	
        	   }, 'json');
          }
  
          /******************************************************   timeline           */
  
           var tl_options ={
                 start_at_slide: 0,
                 //debug : true,
                 timenav_height_percentage: 100,
                scale_factor: 	10,
                 initial_zoom: 10, //144, //34,
                 //verlangsamt die Sache!
                 zoom_sequence:	[0.5, 1, 2, 3, 5, 8, 13, 21, 34, 55, 89, 144, 233, 377], //, 610
                 use_bc: true,
                 language: 'de',
               //  storyslider_height: 0,
                 //start_zoom_adjust: 8,
                 start_at_slide: parseInt(timeline_json.events.length / 2)
             }

    
             App.timeline = new TL.Timeline('timeline', timeline_json, tl_options);
            

             $('#timeline').delegate(".tl-timemarker", "click", function () {
                 tmp = this.id.split("-");
                 var id = tmp[0];
                 //evtl. geöffnete Fenster zu
                 if ($("#contentOverlay").size() > 0) {
         			$("#contentOverlay").remove()
         		}
                App.drawMarkers([id]);
               // console.log("Marker::"+id);
                if(!App.hasLocation(id))
                    App.showContent(id);
                //clean checked categories
                App.cleanUpCategories();
             });
             
        App.hasLocation = function(id){
             for(i in geoJsonData[0].features ){
                    if(geoJsonData[0].features[i].id == id)
                        return true;
                  
		        
        	    }
        	    return false;
        }
           
      }//App not undefined 
     
  /******************************************************   list filter           *****/
  
  if (typeof (FilterApp) != 'undefined') {
      
      var filterArr = [];
      var filterIDArr = [];
      var $filters = $('.categorylist li a');
      var $items = $('.item');
      
      if(preselectedCat != ''){
          filterArr.push(preselectedCat);
          filterIDArr.push(preselectedCatID);
          selector = filterArr.join(", "); 
          $items.filter(selector).slideDown();
            //filter optik
          $("[data-filter='" + preselectedCat + "']").addClass('filter-active');
          
      }
      
      
      $filters.click(function(){ //filter an
          
          if($(this).attr('id') == 'cat_neu'){
              
              
                  if($(this).hasClass('filter-active')){
                  
                      $( this ).removeClass('filter-active'); 
                     // App.resetMarkers(); 
                  }else{
                  
                      $('.filter-active').each(function(i, obj) {
                          $(obj).removeClass('filter-active'); 
                      });
                      filterIDArr = ['cat_neu'];
                  
                      $(this).addClass('filter-active');
                      FilterApp.getFiltered();
                  }
            }else{
                    
                  var selector ='';
                  var thisFilter = $(this).data('filter'); 
                  var thisFilterID = $(this).data('termid'); 
                  
                  if($('#cat_neu').hasClass('filter-active')){
                      $('#cat_neu').removeClass('filter-active');
                      filterIDArr = [];
                  }
         
                 if($.inArray(thisFilter, filterArr) < 0){ //an
                     filterArr.push(thisFilter);
                     filterIDArr.push(thisFilterID);
             
                     FilterApp.getFiltered();
                      $( this ).addClass('filter-active');
              
                 }else{ //aus
                     filterArr = $.grep(filterArr, function(value) {
                       return value != thisFilter;
                     });
                     filterIDArr = $.grep(filterIDArr, function(value) {
                        return value != thisFilterID;
                      });
      
                     FilterApp.getFiltered();
                     $( this ).removeClass( "filter-active" );
              
                 }
             }
         
      
         return false;
       });
       
       
        FilterApp.getNewest = function(){
              var o = {};
          
                o.action = 'getNewest';

               $.post(my_ajax_object.ajax_url, o, function(response) {
                   $('#contentWrapper').slideUp('slow');
                    $('#contentWrapper tbody').empty();
                   if(response.error == "N"){
                         
                          var allHtml = [];
                      
                          $.each(response.posts, function( i, obj ) {
                              var html ='';
                                  html += '<tr class="item "><td class="zeitraum"><span>'+response.posts[i].displayDate+'</span></td>';
                                  html += '<td class="inhalt"><h2><a href="'+response.posts[i].permalink+'">'+response.posts[i].post_title+'</a></h2>';
                                  if(response.posts[i].video != '')
                                      html += '<div class="videoWrapper">'+response.posts[i].video+'</div>';
                                  html += '<div>'+response.posts[i].post_content+'</div>';
                                  html += '</td></tr>';
                                  if(response.posts[i].audio){
                                         html += '<div class="audioWrapper">';
                                         if(response.posts[i].audio_bild)
                                             html += '<div class="audio_img">'+response.posts[i].audio_bild+'</div>';
                                         html += response.posts[i].audio+'</div>';
                                 }
                                 if(response.posts[i].bilder){
                                     html += '<div class="sliderWrapper">';
                                     $.each(response.posts[i].bilder, function( i, obj ) {
                                         html += '<div class="slick-container">';
                                         html += '<img src="'+obj.bild+'" alt="'+obj.alt+'">';
                                         html += '<div class="caption">'+obj.caption+'</div>';
                                         html += '<div class="caption">Quelle: <i>'+obj.quelle+'</i></div>';
                                         html += '</div>';
                                        //console.log("bilder:::"+obj.quelle);

                                     });

                                     html += '</div>';
                                 }
                                 // 
                                  $('#contentWrapper tbody').append(html); 
                                  $('#contentWrapper').slideDown('slow');

                          });

                      
                     }else{
                         var html ='';
                             html += '<tr class="item "><td class="zeitraum"><span></span></td>';
                             html += '<td class="inhalt"><div> Es gibt keine Einträge zu den gewählten Kriterien</div></td</tr>';
                             
                             $('#contentWrapper tbody').append(html);
                             $('#contentWrapper').slideDown('slow');

                     }


          	   }, 'json');
         }
          
          
        FilterApp.getFiltered = function(){
                    
            
                    if(timeMin.indexOf(".") > 0){
                        var tmp = timeMin.split(".") ;
                        var min = tmp[0];
                    }else{
                        var min = timeMin;
                    }

                    if(timeMax.indexOf(".") > 0){
                          var tmp = timeMax.split(".") ;
                          var max = tmp[0];
                      }else{
                          var max = timeMax;
                      }
                      
                    var o = {};
                     o.min = min;
                     o.max = max;
                     o.filterArr = filterIDArr;
                     o.action = 'filterByYearAndKeyword';
                 
                   // console.log("filter  ::"+filterIDArr.join(", ")+'----zeitraum::'+timeMin+'--'+timeMax)
                    $.post(my_ajax_object.ajax_url, o, function(response) {
                         $('#contentWrapper').slideUp('slow');
                         $('#contentWrapper tbody').empty();
                        if(response.error == "N"){
                              
                               var allHtml = [];
                           
                               $.each(response.posts, function( i, obj ) {
                                   var html ='';
                                       html += '<tr class="item "><td class="zeitraum"><span>'+response.posts[i].displayDate+'</span></td>';
                                       html += '<td class="inhalt"><h2><a href="'+response.posts[i].permalink+'">'+response.posts[i].post_title+'</a></h2>';
                                       if(response.posts[i].video != '')
                                           html += '<div class="videoWrapper">'+response.posts[i].video+'</div>';
                                           
                                           if(response.posts[i].audio){
                                               html += '<div class="audioWrapper">';
                                               if(response.posts[i].audio_bild)
                                                   html += '<div class="audio_img">'+response.posts[i].audio_bild+'</div>';
                                               html += response.posts[i].audio+'</div>';
                                           }
                                           if(response.posts[i].bilder){
                                               html += '<div class="sliderWrapper">';
                                               $.each(response.posts[i].bilder, function( i, obj ) {
                                                   html += '<div class="slick-container">';
                                                   html += '<img src="'+obj.bild+'" alt="'+obj.alt+'">';
                                                   html += '<div class="caption">'+obj.caption+'</div>';
                                                   html += '<div class="caption">Quelle: <i>'+obj.quelle+'</i></div>';
                                                   html += '</div>';
                                                  //console.log("bilder:::"+obj.quelle);

                                               });

                                               html += '</div>';
                                           }
                                           
                                       html += '<div>'+response.posts[i].post_content+'</div>';
                                       html += '</td></tr>';
                                      
                                      // 
                                       $('#contentWrapper tbody').append(html); 
                                       $('#contentWrapper').slideDown('slow');

                                       if($('.sliderWrapper').length){
                                           $(".sliderWrapper").not('.slick-initialized').slick({
                                            // $('.sliderWrapper').slick({
                                                 dots: true,
                                                 speed: 500
                                               });
                                           }

                               });
                           //   //Kampagne
                           //   if (typeof (response.campaign_title) != 'undefined') {
                           //       var html = ' <div class="close_overlay"><a href="#" ><i class="mdi mdi-close icon"></i></a></div>';
                           //           html += '<h2>'+response.campaign_title+'</h2>';
                           //           html += '<p>'+response.campaign_txt+'</p>';
                           //       $('#campaign').html(html);
                           //       $('#campaign').show();
                           //       //nun closebutton init
                           //       $('.close_overlay').click(function(e){
                           //           e.preventDefault();
                           //           $('#campaign').html('');
                           //           $('#campaign').hide();
                           //       });
                           //   }else{
                           //       $('#campaign').html('');
                           //       $('#campaign').hide();
                           //   }
                           
                          }else{
                              var html ='';
                                  html += '<tr class="item "><td class="zeitraum"><span></span></td>';
                                  html += '<td class="inhalt"><div> Es gibt keine Einträge zu den gewählten Kriterien</div></td</tr>';
                                  
                                  $('#contentWrapper tbody').append(html);
                                  $('#contentWrapper').slideDown('slow');

                          }
               
               
               	   }, 'json');
               	 
       }
      
    
    
       /******************* time slider on list view*****************/   

           var timeMin = sliderStart;
           var timeMax = sliderEnd;
           var flag = false;
           
            $("#slider").editRangeSlider({
                  arrows:false,
                  bounds: {
                      min: parseInt(startYear),
                      max: parseInt(thisYear)  
                  },
                  defaultValues:{
                      min: sliderStart,
                      max: sliderEnd
                  }
            });
             
            $("#slider").on("valuesChanged", function(e, data){
              console.log("Something moved. min: " + data.values.min + " max: " + data.values.max);
              
              
              if(String(data.values.min) != sliderStart || String(data.values.max) != sliderEnd || flag == true){
                  flag = true;
                  timeMin = String(data.values.min);
                  timeMax = String(data.values.max);
                  FilterApp.getFiltered();
                  
            	}
               
            });
            
            
            
              
        
  }

      if($('.sliderWrapper').length){
          $('.sliderWrapper').slick({
              dots: true,
              speed: 500
            });
        }
        
        
        
       
});