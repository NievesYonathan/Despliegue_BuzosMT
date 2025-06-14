$(document).ready(function () {

	/*  Show/Hidden Submenus */
	$('.nav-btn-submenu').on('click', function (e) {
		e.preventDefault();
		let SubMenu = $(this).next('ul');
		let iconBtn = $(this).children('.fa-chevron-down');
		if (SubMenu.hasClass('show-nav-lateral-submenu')) {
			$(this).removeClass('active');
			iconBtn.removeClass('fa-rotate-180');
			SubMenu.removeClass('show-nav-lateral-submenu');
		} else {
			$(this).addClass('active');
			iconBtn.addClass('fa-rotate-180');
			SubMenu.addClass('show-nav-lateral-submenu');
		}
	});

	/*  Show/Hidden Nav Lateral */
	$('.show-nav-lateral').on('click', function (e) {
		e.preventDefault();
		let NavLateral = $('.nav-lateral');
		let PageConten = $('.page-content');
		if (NavLateral.hasClass('active')) {
			NavLateral.removeClass('active');
			PageConten.removeClass('active');
		} else {
			NavLateral.addClass('active');
			PageConten.addClass('active');
		}
	});

	/*  Exit system buttom */
	$('.btn-exit-system').on('click', function (e) {
		e.preventDefault();
		Swal.fire({
			title: 'Are you sure to close the session?',
			text: "You are about to close the session and exit the system",
			type: 'question',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, exit!',
			cancelButtonText: 'No, cancel'
		}).then((result) => {
			if (result.value) {
				window.location = "../Home-Page-MT/V-2/index.html";
			}
		});
	});
});
(function ($) {
	$(window).on("load", function () {
		$(".nav-lateral-content").mCustomScrollbar({
			theme: "light-thin",
			scrollbarPosition: "inside",
			autoHideScrollbar: true,
			scrollButtons: { enable: true }
		});
		$(".page-content").mCustomScrollbar({
			theme: "dark-thin",
			scrollbarPosition: "inside",
			autoHideScrollbar: true,
			scrollButtons: { enable: true }
		});
	});
})(jQuery);
