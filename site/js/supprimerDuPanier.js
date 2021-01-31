function supprimerDuPanier(idArticlePanier){

    swal({
        title: "Êtes vous sur de vouloir supprimer cet article ?",
        text: "C'est pourtant super bon !",
        icon: "warning",
        buttons: true,
        dangerMode: true,
      })
      .then((willDelete) => {
        if (willDelete) {
            if (document.ax){
                document.ax.cancel();
            }
        
            document.ax=	new AjaxRequest({
                url        : "supprimerDuPanier.php",
                method     : 'get',
                handleAs   : 'text',
                parameters : {
                idArticle  : idArticlePanier,
                wait: null,
                },
        
                onSuccess  : function(res) {
                    document.ax = null;
                    swal("Poof! L'article a disparu du panier !", {icon: "success", buttons: false,});
                    setTimeout(function(){ window.location.reload(); }, 1000);
                },
        
                onError    : function(status, message) {
                    window.alert('Error ' + status + ': ' + message) ;
                }
            })
        }
      });

}