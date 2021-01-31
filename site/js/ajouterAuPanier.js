function ajouterAuPanier(idArticle, idUser) {

    if (document.ax) {
        document.ax.cancel();
    }

    document.ax = new AjaxRequest({
        url: "ajouterAuPanier.php",
        method: 'get',
        handleAs: 'text',
        parameters: {
            idArticle: idArticle,
            idUser: idUser,
            wait: null,
        },

        onSuccess: function (res) {
            document.ax = null;
            swal("Bien joué", "Vous avez ajouté un article au panier!", "success");
        },

        onError: function (status, message) {
            window.alert('Error ' + status + ': ' + message);
        }
    })



}