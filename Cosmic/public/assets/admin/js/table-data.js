var Page = {
	createDataTableNormal: function(){
        $('#desc').DataTable( {
            "order": [[ 0, "desc" ]]
        });

        $('#asc').DataTable( {
            "order": [[ 0, "asc" ]]
        });



        $('#playerList').DataTable( {
            "order": [[ 0, "desc" ]]
        });

        $('#banList').DataTable( {
            "order": [[ 0, "desc" ]]
        });

        $('#expireList').DataTable( {
            "order": [[ 0, "asc" ]]
        });

        $('#roomList').DataTable( {
            "order": [[ 3, "desc" ]]
        });

        $('#newsList').DataTable( {
            "order": [[ 0, "desc" ]]
        });

        $('#logs').DataTable( {
            "order": [[ 0, "asc" ]]
        });

    },
    createDataTableTwo: function(){
        $('#table-two').DataTable({
            "pagingType": "simple" 
        });	
    },
    createDataTableFour: function(){
        $('#table-four').DataTable({
            "pagingType": "full" 
        });	
    },
	init:function() {
		this.createDataTableNormal();
        this.createDataTableTwo();
        this.createDataTableFour();
	}
};