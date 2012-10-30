
var Main = new(function()
{
	// this.transEndEventName;
	var _self = this;
	var _isSubmitting = false;
	var _isExistingStudent = false;
	var _username = "";
	var _currentClassType = "";
	var _currentClassID = "";

	function _resized()
	{
	};

	this.init = function init( $page )
	{
		$( window ).resize( _resized );
		_resized();

		cookie.defaults.expires = 7;

		// sk: not ideal but will work for now
		if( $page === 'index' )
		{
			$( '#logout-btn' ).click( _logout );
			// $( '#create-yoga' ).click( _createYoga );
			$( '.creation' ).click(function(){
				var type = $( this ).attr( 'id' );
				_createClassOfType( type );
			});
			// $( '#create-yoga' ).click(function(){
			// 	_createClassOfType( 'yoga' );
			// });
			// $( '#create-pilates' )click(function(){
			// 	_createClassOfType( 'pilates' );
			// });
			// $( '#create-special' )click(function(){
			// 	_createClassOfType( 'special' );
			// });

			$( '.add-student' ).click( _addStudentToForm );
			$( '#add-student-to-class' ).click( _addStudentToClass );
			$( '.delete-from-class' ).click( _deleteStudentFromClass );
			$( '#save-class' ).click( _saveClass );
			$( '#goods' ).click( _onSelectGoods );

			if( cookie( 'cobra_class_id' ) )
			{
				var classElements = cookie( 'cobra_class_id' ).split( '-' );
				var classTeacher = classElements[0];
				if( classTeacher === cookie( 'cobra_login' ) )
				{
					_currentClassID = classElements[2];
					_currentClassType = classElements[1];

					_showForms( _currentClassID );
				}
				// $( '#students-dropdown' ).show();
			}
			// else
			// {
			// 	$( '#students-dropdown' ).hide();
			// }
		}else if( $page === 'class-admin' )
		{
			$( '.pay-class' ).click( _payClass );
		}


		// if( $('#newsletter-signup-name') )
		// {
		// 	$('#newsletter-signup-name').focus( _clearPlaceholder );
		// 	$('#newsletter-signup-email').focus( _clearPlaceholder );
		// 	$('#newsletter-signup-name').blur( _addPlaceholder );
		// 	$('#newsletter-signup-email').blur( _addPlaceholder );
		// };
		// // sk: set the CSS transition end name so we can listen to it
		// var transEndEventNames = {
		// 	'WebkitTransition' : 'webkitTransitionEnd',
		// 	'MozTransition'    : 'transitionend',
		// 	'OTransition'      : 'oTransitionEnd',
		// 	'msTransition'     : 'MSTransitionEnd',
		// 	'transition'       : 'transitionend'
		// };

		// _self.transEndEventName = transEndEventNames[ Modernizr.prefixed( 'transition' ) ];

		// $('.orbit-marquee').css( 'opacity', 1 );
		// _self.setOpacity( ['#container'], 1 );
	};
	function _onSelectGoods( $evt )
	{
		var card_available = false;
		$( '#goods :selected' ).each(function( i, selected )
		{
			var txt = $( selected ).text();
			if( txt.indexOf( 'Class Card' ) != -1 )
			{
				card_available = true;
			}
		});

		if( card_available )
		{
			$( '#class-card' ).removeClass( 'hidden' );
		}else
		{
			$( '#class-card' ).addClass( 'hidden' );
		}
	};

	// function _clearPlaceholder( $evt )
	// {
	// 	$(this).attr( 'placeholder', '' );
	// };
	// function _addPlaceholder( $evt )
	// {
	// 	if( $(this).attr( 'id' ) == 'newsletter-signup-name' )
	// 	{
	// 		$(this).attr( 'placeholder', 'Name*' );
	// 	}else
	// 	{
	// 		$(this).attr( 'placeholder', 'Email*' );
	// 	}
	// };

	function _createClassOfType( $type )
	{
		_currentClassType = $type;

		var userData =
		{
			// name: cookie( 'username' ),
			login: cookie( 'cobra_login' ),
			type: _currentClassType,
			sid: cookie( 'cobra_sid' ),
			timestamp: new Date().getTime()
		};

		$.post(
			"http://skoch.local/cobra-admin/php/addClass.php",
			{ data: userData },
			function( $data )
			{
				console.log( '$data', $data );
				if( $data.result == 'class added' )
				{
					_currentClassID = $data.class_id['$id'];
					cookie.set({
						cobra_class_id: $data.login + '-' + _currentClassType + '-' + _currentClassID
					});
					_showForms( _currentClassID );
				}
			},
			"json"
		);
	};

	// function _createYoga( $evt )
	// {
	// 	console.log( '_createYoga' );


	// };
	// function _createPilates( $evt )
	// {
	// 	var cookies = cookie.all();
	// 	console.log( 'cookies', cookies );
	// };
	function _createSpecial( $evt )
	{

	};

	function _showForms( $class_id )
	{
		$( '#class-form' ).removeClass( 'hidden' );
		$( '#student-form' ).removeClass( 'hidden' );
		$( '#add-class' ).addClass( 'disabled' );
		$( '#students-dropdown' ).show();

		var timestamp = $class_id.toString().substring( 0, 8 );
		var date = new Date( parseInt( timestamp, 16 ) * 1000 );
		// $( '#class-caption' ).html(
		// 	'<span class="label label-info">Class Type:</span> ' +
		// 	$type.toUpperCase() + '<br><span class="label label-info">Teacher:</span> ' +
		// 	cookie( 'cobra_username' ) + '<br><span class="label label-info">Date:</span> ' + date
		// );
		$( '#class-caption' ).html(
			'Class Type: ' + _currentClassType.toUpperCase() + '<br>Teacher: ' + cookie( 'cobra_username' ) + '<br>Date: ' + date
		);

		var cost = '';
		switch( _currentClassType )
		{
			case 'yoga' :
				cost = '12';
			break;

			case 'special' :
			case 'pilates' :
				cost = '15';
			break;
		}

		$( '#goods' ).prepend( '<option value="' + cost + '">Class ($' + cost + ')</option>' );
	};
	function _addStudentToForm( $evt )
	{
		$evt.preventDefault();

		_isExistingStudent = true;

		var $$ = $( $evt.target );
		var username = $$.attr( 'data-username' );
		var id = $$.attr( 'data-id' );
		var fullname = $$.attr( 'data-fullname' );
		var card = $$.attr( 'data-card' );
		var total_classes_taken = $$.attr( 'data-total-classes-taken' );
		var classes_taken_with_card = $$.attr( 'data-classes-taken-with-card' );
		var classes_left = card - classes_taken_with_card;

		if( _currentClassType == 'yoga' && classes_left > 0 )
		{
			$( '#class-card' ).removeClass( 'hidden' );
			$( '#class-card' ).html(
				'<input type="radio" value="class card" name="class-payment">' +
				'Class card (' + classes_left + ' left of ' + card + ' : meaning after the class is saved, it will be one less )'
			);

			$( '#class-card input' ).prop( "checked", true );
		}

		$( '#student-name' ).val( fullname );
		$( '#student-id' ).val( id );
	};

	function _showError( $msg )
	{
		var alert = '<div class="alert alert-error"><button type="button" class="close" data-dismiss="alert">×</button><strong>Ooops!</strong> ' + $msg + '</div>';
		$( '#form-errors' ).append( alert );
	};

	function _addStudentToClass( $evt )
	{
		$evt.preventDefault();

		var student_name = $( '#student-name' ).val();
		if( student_name === '' )
		{
			_showError( "Name cannot be empty." );
			return;
		}
		var student_id = $( '#student-id' ).val();
		// var class_payment = $('input[name=class-payment]:checked', '#student-form').val();
		var payment = $('input[name=payment]:checked', '#student-form').val();

		// sk: not sure how to use a boolean here so this is hack
		var use_class_card = false;
		if( $('input[name=use-class-card]:checked', '#student-form').val() === 'Yes' )
		{
			use_class_card = true;
		}

		var goods_display = "";
		// var total = ( _currentClassType == 'yoga' ) ? 12 : 15;
		var total = 0;
		var purchased_class_card = false;
		// var is_gift_card = false;
		var card_value = 0;
		var cards = [];
		if( $('#goods').val() )
		{
			$( '#goods :selected' ).each(function( i, selected )
			{
				var txt = $( selected ).text();
				if( txt.indexOf( 'Class Card' ) != -1 )
				{
					// card_value = parseInt( txt.split( ' ' )[3] );
					// var card_is_gift = ( txt.indexOf( 'gift' ) != -1 ) ? true : false;
					cards.push({
						value: parseInt( txt.split( ' ' )[3] )
						// is_gift: card_is_gift,
					});
					card_value = parseInt( txt.split( ' ' )[3] );
					purchased_class_card = true;
					// if(  )
					// {
					// 	is_gift_card = true;
					// };

					// Class Card - 10 classes ($35)
					// Class Card - 10 classes ($35 - gift)
				}
				goods_display += txt + ", ";
				total += parseInt( $( selected ).val(), 10 ) || 0;
			});
		}else
		{
			goods_display = "—";
		}

		// did they choose more than one card?
		if( cards.length > 1 )
		{
			_showError( "Each card must me it's own entry." );
			return;
		}

		// for( var i = cards.length - 1; i >= 0; i-- )
		// {
		// 	if( ! cards[i].is_gift )
		// 	{
		// 		purchased_class_card = true;
		// 		card_value = cards[i].value;
		// 		break;
		// 	}
		// }

		var userData =
		{
			fullname: student_name,
			student_id: student_id,
			class_id: _currentClassID,
			existing_student: _isExistingStudent,
			purchased_class_card: purchased_class_card,
			card_value: card_value,
			use_class_card: use_class_card,
			payment: payment,
			goods_display: goods_display,
			total: total,
			timestamp: new Date().getTime()
		};

		// console.log( 'userData', userData );
		// return;
		// console.log( 'student_name', student_name );
		// console.log( '_isExistingStudent', _isExistingStudent );
		// console.log( 'purchased_class_card', purchased_class_card );
		// console.log( 'card_value', card_value );
		// console.log( 'class_payment', class_payment );
		// console.log( 'payment', payment );
		// console.log( 'goods_display', goods_display );
		// console.log( 'total', total );

		$.post(
			"http://skoch.local/cobra-admin/php/addStudentToClass.php",
			{ data: userData },
			function( $data )
			{
				if( $data.success )
				{
					console.log( 'ADDED STUDENT', $data.student_id );
					// reset student form
					_resetStudentForm();
					// add student to table
					// var display_student_id = student_id;
					// if( $data.new_student_id != "" )
					// {
					// 	display_student_id = $data.student_id;
					// }
					var student_info = '<tr id="' + $data.student_id + '">';
					student_info += '<td>' + student_name + '</td>';
					if( purchased_class_card )
					{
						student_info += '<td>yes</td>';
					}else
					{
						student_info += '<td>—</td>';
					}
					student_info += '<td>' + goods_display.substring( 0, goods_display.length - 2 ) + '</td>';
					student_info += '<td>' + payment + '</td>';

					// if( class_payment === 'class card' )
					// {
					//     ttl = total;
					// }else
					// {
					//     if( _currentClassType === 'yoga' )
					//     {
					//         ttl = total + 12;
					//     }else
					//     {
					//         ttl = total + 15;
					//     }
					// }

					student_info += '<td>' + total + '</td>';
					var delete_btn = '<button class="btn btn-mini btn-danger delete-from-class" data-payment="' + payment + '"';
					delete_btn += 'data-id="' + $data.student_id + '" type="button">Delete</button>';
					student_info += '<td class="delete-student">' + delete_btn + '</td>';
					student_info += '</tr>';

					$( '#students' ).append( student_info );
				}
			},
			"json"
		);
	};

	function _deleteStudentFromClass( $evt )
	{
		$evt.preventDefault();

		var data =
		{
			student_id: $( $evt.target ).attr( 'data-id' ),
			class_payment: $( $evt.target ).attr( 'data-payment' ),
			class_id: _currentClassID
		};

		$.post(
			"http://skoch.local/cobra-admin/php/deleteStudentFromClass.php",
			{ data: data },
			function( $data )
			{
				// if( $data.result == 'student deleted from class' )
				if( $data.success )
				{
					$( '#' + $data['student_id'] ).remove();
				}
			},
			"json"
		);
	};

	function _saveClass( $evt )
	{
		$evt.preventDefault();

		$.post(
			"http://skoch.local/cobra-admin/php/saveClass.php",
			{ class_id: _currentClassID },
			function( $data )
			{
				if( $data.success )
				{
					console.log( 'class is saved', $data['student_ids'] );
					for( var i = $data['student_ids'].length - 1; i >= 0; i-- )
					{
						$( '#' + $data['student_ids'][i] ).remove();
					}
					$( '#class-caption' ).html( '' );
					cookie.remove( 'cobra_class_id' );

					_resetStudentForm();

					_isExistingStudent = false;
					_currentClassType = "";
					_currentClassID = "";

					$( '#class-form' ).addClass( 'hidden' );
					$( '#student-form' ).addClass( 'hidden' );
					$( '#add-class' ).removeClass( 'disabled' );

					// clear form, hide, enable add class btn
					// delete relevant cookies

					$( '#success-message' ).removeClass( 'hidden' );
					$( '#students-dropdown' ).hide();
				}
			},
			"json"
		);
	};

	function _payClass( $evt )
	{
		$evt.preventDefault();

		var class_id = $( $evt.target ).attr( 'data-class-id' );
		$.post(
			"http://skoch.local/cobra-admin/php/payClass.php",
			{ class_id: class_id },
			function( $data )
			{
				if( $data.result == 'class paid' )
				{
					$( '#class-' + class_id ).remove();
				}
			},
			"json"
		);
	};
	function _resetStudentForm()
	{
		$( '#student-name' ).val( '' );
		$( '#class-card' ).addClass( 'hidden' );
		$( '#goods' ).each(function( i, item )
		{
			$(item).removeAttr( 'selected' );
		});
		$( '#student-id' ).val( '' );
	};

	function _logout( $evt )
	{
		$evt.preventDefault();

		var userData =
		{
			name: cookie( 'cobra_username' ),
			timestamp: new Date().getTime()
		};

		$.post(
			"http://skoch.local/cobra-admin/php/logout.php",
			{ data: userData },
			function( $data )
			{
				if( $data.result == 'user logged out' )
				{
					cookie.remove( 'cobra_username', 'cobra_login', 'cobra_sid' );
					window.location.href = 'http://skoch.local/cobra-admin/login.php';
				}
			},
			"json"
		);
	};
	function _getFriendlyDate( $date )
	{
		var hours = $date.getHours();
		var minutes = $date.getMinutes();

		if( minutes < 10 ) minutes = "0" + minutes

		var suffix = "AM";
		if( hours >= 12)
		{
			suffix = "PM";
			hours = hours - 12;
		}
		if( hours == 0 )
		{
			hours = 12;
		}

		var day = $date.getDate();
		var month = $date.getMonth() + 1;
		var year = $date.getFullYear();

		return hours + ":" + minutes + " " + suffix + " " + year + "-" + month + "-" + day;
	};

	// this.setOpacity = function setOpacity( $elements, $alpha )
	// {
	// 	for( var i = $elements.length - 1; i >= 0; i-- )
	// 	{
	// 		if( Modernizr.csstransitions )
	// 		{
	// 			$( $elements[i] ).css( 'opacity', $alpha );
	// 		}else
	// 		{
	// 			$( $elements[i] ).animate( {opacity: $alpha}, 200 );
	// 		}

	// 	};
	// };


	function _openInNewWindow( $event )
	{
		var target = $( $event.target );
			$event.preventDefault();

		if (
			target.length &&
			! target.is( 'a' )
		)
		{
			target = target.closest( 'a' )
		}

		if ( target.length )
		{

			var url = target.attr( 'href' );

			if ( url )
			{
				window.open( url );
			}
		}
	}

	function _setBrowser()
	{
		var userAgent = navigator.userAgent.toLowerCase();
		// console.log( 'useragent = ' + userAgent );

		// Figure out what browser is being used
		jQuery.browser = {

			version: (userAgent.match( /.+(?:rv|it|ra|ie|me|ve)[\/: ]([\d.]+)/ ) || [])[1],

			chrome: /chrome/.test( userAgent ),
			safari: /webkit/.test( userAgent ) && !/chrome/.test( userAgent ),
			opera: /opera/.test( userAgent ),
			firefox: /firefox/.test( userAgent ),
			msie: /msie/.test( userAgent ) && !/opera/.test( userAgent ),

			mozilla: /mozilla/.test( userAgent ) && !/(compatible|webkit)/.test( userAgent ),

			webkit: $.browser.webkit,
			gecko: /[^like]{4} gecko/.test( userAgent ),
			presto: /presto/.test( userAgent ),

			xoom: /xoom/.test( userAgent ),

			android: /android/.test( userAgent ),
			androidVersion: (userAgent.match( /.+(?:android)[\/: ]([\d.]+)/ ) || [0,0])[1],

			iphone: /iphone|ipod/.test( userAgent ),
			iphoneVersion: (userAgent.match( /.+(?:iphone\ os)[\/: ]([\d_]+)/ ) || [0,0])[1].toString().split('_').join('.'),

			ipad: /ipad/.test( userAgent ),
			ipadVersion: (userAgent.match( /.+(?:cpu\ os)[\/: ]([\d_]+)/ ) || [0,0])[1].toString().split('_').join('.'),

			blackberry: /blackberry/.test( userAgent ),

			winMobile: /Windows\ Phone/.test( userAgent ),
			winMobileVersion: (userAgent.match( /.+(?:windows\ phone\ os)[\/: ]([\d_]+)/ ) || [0,0])[1]
		};

		jQuery.browser.mobile   =   ($.browser.iphone || $.browser.ipad || $.browser.android || $.browser.blackberry );
	};

	// construct
	$(function()
	{
		_setBrowser();
	});

})();