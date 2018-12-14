Vue.use(VueMaterial.default);
Vue.config.devtools = true;
var users = new Vue({
    delimiters: ['${', '}'],
    el: '#UserPage',
    data: {
        options: '',
        user: '',
        email: '',
        company: '',
        contData: '',
        companiesData: '',
        userID: '',
        showDialog: false,
        showEdirDialog: false,
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
                    users.usersList();
                    M.toast({html: 'Saved!'}, 4000);
                    return vm.options;

                })
                .catch(function (error) {
                    M.toast({html: 'Error!'}, 4000);
                    console.log(error.message);
                });
        },
        EditUser: function () {
            var vm = this;
            axios.post('/api/users/edit', {
                'id': this.userID,
                'name': this.user,
                'email': this.email,
                'company': this.company,
            })
                .then(function (response) {
                    console.log(response.data);
                    users.usersList();
                    M.toast({html: 'Saved!'}, 4000);
                    return vm.options;

                })
                .catch(function (error) {
                    M.toast({html: 'Error!'}, 4000);
                    console.log(error.message);
                });
        },
        usersList: function () {
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
                    users.usersList();
                })
                .catch(function (error) {
                    M.toast({html: 'Error!'});
                    console.log(error.message);
                });
        }
    }
});


var string2 = window.location.href,
    substring2 = "/users";
if (string2.indexOf(substring2) !== -1) {
    users.usersList();
    users.loadCompaniesList();
}

var companies = new Vue({
    delimiters: ['${', '}'],
    el: '#CompaniesPage',
    data: {
        options: '',
        user: '',
        name: '',
        company: '',
        quota: '',
        contData: '',
        companiesData: '',
        companyID: '',
        showDialog: false,
        showEdirDialog: false,
    },
    methods: {
        addNewCompany: function () {
            var vm = this;
            console.log(this.user);
            axios.post('/api/companies/add', {
                'name': this.name,
                'quota': this.quota,
            })
                .then(function (response) {
                    console.log(response.data);
                    companies.companiesList();
                    M.toast({html: 'Saved!'}, 4000);
                    return vm.options;

                })
                .catch(function (error) {
                    M.toast({html: 'Error!'}, 4000);
                    console.log(error.message);
                });
        },
        EditCompany: function () {
            var vm = this;
            axios.post('/api/companies/edit', {
                'id': this.companyID,
                'name': this.name,
                'quota': this.quota,
            })
                .then(function (response) {
                    console.log(response.data);
                    companies.companiesList();
                    M.toast({html: 'Saved!'}, 4000);
                    return vm.options;

                })
                .catch(function (error) {
                    M.toast({html: 'Error!'}, 4000);
                    console.log(error.message);
                });
        },
        companiesList: function () {
            var vm = this;
            axios.get('/api/companies/list')
                .then(function (response) {
                    vm.contData = JSON.parse(response.data);
                    return vm.contData;
                })
                .catch(function (error) {
                    console.log(error.message);
                });
        },
        onDelete: function (e) {
            var vm = this;
            var ID = e.path[2].value;
            axios.delete('/api/companies/delete/' + ID)
                .then(function (response) {
                    M.toast({html: 'Deleted!'});
                    companies.companiesList();
                })
                .catch(function (error) {
                    M.toast({html: 'Error!'});
                    console.log(error.message);
                });
        }
    }
});

var string = window.location.href,
    substring = "/companies";
if (string.indexOf(substring) !== -1) {
    companies.companiesList();
}


var main = new Vue({
    delimiters: ['${', '}'],
    el: '#mainPage',
    data: {
        options: '',
        user: '',
        name: '',
        company: '',
        quota: '',
        contData: '',
        monthsData: '',
        months: '',
        companiesData: '',
        reportData: '',
        companyID: '',
        showDialog: false,
        showEdirDialog: false,
    },
    methods: {
        generateData: function () {
            var vm = this;
            axios.get('/api/generate')
                .then(function (response) {
                    M.toast({html: 'Generated!'}, 4000);
                })
                .catch(function (error) {
                    console.log(error.message);
                });
        },
        getMonths: function () {
            var vm = this;
            axios.get('/api/last_months')
                .then(function (response) {

                    console.log(response.data);
                    vm.monthsData = response.data;
                    return vm.monthsData;
                })
                .catch(function (error) {
                    console.log(error.message);
                });
        },
        getReport: function () {
            var vm = this;
            axios.post('/api/list_report', {
                'months': this.months,
            })
                .then(function (response) {
                    console.log(JSON.parse(response.data));
                    vm.reportData = JSON.parse(response.data);
                    return vm.reportData;
                })
                .catch(function (error) {
                    M.toast({html: 'Error!'}, 4000);
                    console.log(error.message);
                });
        },
    }
});


var string3 = window.location.href,
    substring3 = "/main";
if (string3.indexOf(substring3) !== -1) {
    main.getMonths();
}
