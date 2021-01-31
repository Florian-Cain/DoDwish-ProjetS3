function finirCommande(numCommande) {

    if (document.ax) {
        document.ax.cancel();
    }

    document.ax = new AjaxRequest({
        url: "finirCommande.php",
        method: 'get',
        handleAs: 'text',
        parameters: {
            numCommande: numCommande,
        },

        onSuccess: function (res) {
            document.ax = null;
            swal("Commande livrée !", { icon: "success", buttons: false, });
            setTimeout(function () { window.location.reload(); }, 1000);
        },

        onError: function (status, message) {
            window.alert('Error ' + status + ': ' + message);
        }
    })



}