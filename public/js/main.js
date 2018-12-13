Vue.use(VueMaterial.default);
Vue.config.devtools = true;
var contacts = new Vue({
    delimiters: ['${', '}'],
    el: '#UserPage',
    data: {
        isEditing: false,
        options: '',
        contAdress1: '',
        contAdress2: '',
        contTime1: '',
        contTime2: '',
        contContacts1: '',
        user: '',
        email: '',
        company: '',
        contData: '',
        companiesData: '',
        showDialog: false,
    },
    methods: {
        addNewUser: function () {
            var vm = this;
            console.log(this.user);
            axios.post('/api/users/add', {
                    'name': this.user,
                    'email': this.email,
                    'company': this.company,
            })
                .then(function (response) {
                    console.log(response.data);
                    contacts.mainContactList();
                    M.toast({html: 'Saved!'}, 4000);
                    return vm.options;

                })
                .catch(function (error) {
                    M.toast({html: 'Error!'}, 4000);
                    console.log(error.message);
                });
        },
        mainContactList: function () {
            var vm = this;
            axios.get('/api/users/list')
                .then(function (response) {
                    vm.contData = JSON.parse(response.data);
                    return vm.contData;
                })
                .catch(function (error) {
                    console.log(error.message);
                });
        },
        loadCompaniesList: function () {
            var vm = this;
            axios.get('/api/companies/list')
                .then(function (response) {
                    vm.companiesData = JSON.parse(response.data);
                    return vm.companiesData;
                })
                .catch(function (error) {
                    console.log(error.message);
                });
        },
        onDelete: function (e) {
            var vm = this;
            var ID = e.path[2].value;
            axios.delete('/api/users/delete/' + ID)
                .then(function (response) {
                    M.toast({html: 'Deleted!'});
                    contacts.mainContactList();
                })
                .catch(function (error) {
                    console.log(error.message);
                });
        },
    }
});


var string2 = window.location.href,
    substring2 = "/users";
if (string2.indexOf(substring2) !== -1) {
    contacts.mainContactList();
    contacts.loadCompaniesList();
}