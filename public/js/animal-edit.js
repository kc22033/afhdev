/* 
 * A Forever Home Rescue Foundation 
 * 
 */
 
 $(document).ready(function () {

	Dropzone.options.myDropzone = { // The camelized version of the ID of the form element

		// The configuration we've talked about above
		autoProcessQueue: true,
		uploadMultiple: false,
		parallelUploads: 6,
		maxFiles: 6,
		url: '/pictures/upload',
		sending: function(file, xhr, formData) {
			// Pass token. You can use the same method to pass any other values as well such as a id to associate the image with for example.
			formData.append("_token", $('[name=_token').val()); // Laravel expect the token post value to be named _token by default
		},	
	}
	
    $('#afh-editor').wysihtml5({
        toolbar: {
            "html": true,
            "color": true,
            "blockquote": false,
            "image": false,
            "size": 'sm'
        }
    });
    $('.date-input').datepicker({
        format: 'yyyy-mm-dd',
        todayBtn: "linked",
        todayHighlight: true,
        autoclose: true
    });

    var fosters = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        limit: 8,
        prefetch: {
            url: '/api/getFosters',
            filter: function (list) {
                return $.map(list, function (foster) {
                    return {name: foster};
                });
            }
        }
    });

    fosters.initialize();

    $('#foster').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    },
    {
        name: 'fosters',
        displayKey: 'name',
        source: fosters.ttAdapter()
    });
});
