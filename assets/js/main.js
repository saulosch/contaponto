//
// SEMPRE QUE ATUALIZAR ESTE ARQUIVO, ATUALIZE TAMBEM SUA VERSAO MINIFIED
// EM https://javascript-minifier.com/
//

jQuery(function($) {'use strict',

	//#main-slider
	// $(function(){
	// 	$('#main-slider.carousel').carousel({
	// 		interval: 8000
	// 	});
	// });


	// accordian
	// $('.accordion-toggle').on('click', function(){
	// 	$(this).closest('.panel-group').children().each(function(){
	// 	$(this).find('>.panel-heading').removeClass('active');
	// 	 });

	//  	$(this).closest('.panel-heading').toggleClass('active');
	// });

	//Initiat WOW JS
	new WOW().init();

	// portfolio filter
	$(window).load(function(){'use strict';
		var $portfolio_selectors = $('.portfolio-filter >li>a');
		var $portfolio = $('.portfolio-items');
		$portfolio.isotope({
			itemSelector : '.portfolio-item',
			layoutMode : 'masonry' //layoutMode : 'fitRows'
		});
		
	 	var filters = {};

	 	$portfolio_selectors.on('click', function(){
	 		//$portfolio_selectors.removeClass('active');

	 		$(this).addClass('active').parent().siblings().children().removeClass('active');




	 		var $buttonGroup = $(this).parents('.portfolio-filter');
	 		var filterGroup = $buttonGroup.attr('data-filter-group');
	 		filters[ filterGroup ] = $(this).attr('data-filter');
	 		var filterValue = concatValues( filters );
			$portfolio.isotope({ filter: filterValue });
	 		return false;
	 	});
	 });

	// store filter for each group
	// 		var filters = {};

	// 		$portfolio_selectors.on( 'click', function() {
	// 			$portfolio_selectors.removeClass('active');
	//  			$(this).addClass('active');
	// 		  var $this = $(this);
	// 		  // get group key
	// 		  var $buttonGroup = $this.parents('.portfolio-filter');
	// 		  var filterGroup = $buttonGroup.attr('data-filter-group');
	// 		  // set filter for group
	// 		  filters[ filterGroup ] = $this.attr('data-filter');
	// 		  // combine filters
	// 		  var filterValue = concatValues( filters );
	// 		  $portfolio.isotope({ filter: filterValue });
	// 		});
	// });

// flatten object by concatting values
function concatValues( obj ) {
  var value = '';
  for ( var prop in obj ) {
    value += obj[ prop ];
  }
  return value;
}

	// Contact form
	// var form = $('#main-contact-form');
	// form.submit(function(event){
	// 	event.preventDefault();
	// 	var form_status = $('<div class="form_status"></div>');
	// 	$.ajax({
	// 		url: $(this).attr('action'),

	// 		beforeSend: function(){
	// 			form.prepend( form_status.html('<p><i class="fa fa-spinner fa-spin"></i> Email is sending...</p>').fadeIn() );
	// 		}
	// 	}).done(function(data){
	// 		form_status.html('<p class="text-success">' + data.message + '</p>').delay(3000).fadeOut();
	// 	});
	// });

	
	//goto top
	$('.gototop').click(function(event) {
		event.preventDefault();
		$('html, body').animate({
			scrollTop: $("body").offset().top
		}, 500);
	});	

	//Pretty Photo
	// $("a[rel^='prettyPhoto']").prettyPhoto({
	// 	social_tools: false
	// });	
});