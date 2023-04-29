

function togglePopup() {
  var popup = jQuery("#filter_popup");
  popup.toggleClass("popup-wrapper");
  jQuery('#filter_popup').css({"display":"block"});
}
// Select the "Clear Filter" button by ID
const clearFilterBtn = jQuery('#clear-filter-btn');

// Add an event listener to the button
clearFilterBtn.on('click', function(e) {
  e.preventDefault(); // Prevent the form from submitting
  jQuery('#post-filter')[0].reset(); // Reset the form
});
