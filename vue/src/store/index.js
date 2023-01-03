import { createStore } from "vuex";
import axiosClient from "../axios";

const store = createStore({
  state: {
    user: {
      data: {},
      token: sessionStorage.getItem("TOKEN"),
    },
    dashboard: {
      loading: false,
      data: {}
    },
    companies: {
      loading: false,
      links: [],
      data: []
    },
    countries: {
      loading: false,
      data: []
    },
    currentCompany: {
      data: {},
      loading: false,
    },
    questionTypes: ["text", "select", "radio", "checkbox", "textarea"],
    notification: {
      show: false,
      type: 'success',
      message: ''
    }
  },
  getters: {},
  actions: {

    register({commit}, user) {
      return axiosClient.post('/register', user)
        .then(({data}) => {
          commit('setUser', data.user);
          commit('setToken', data.token)
          return data;
        })
    },
    login({commit}, user) {
      return axiosClient.post('/login', user)
        .then(({data}) => {
          commit('setUser', data.user);
          commit('setToken', data.token)
          return data;
        })
    },
    logout({commit}) {
      return axiosClient.post('/logout')
        .then(response => {
          commit('logout')
          return response;
        })
    },
    getUser({commit}) {
      return axiosClient.get('/user')
      .then(res => {
        console.log(res);
        commit('setUser', res.data)
      })
    },
    getDashboardData({commit}) {
      commit('dashboardLoading', true)
      return axiosClient.get(`/dashboard`)
      .then((res) => {
        commit('dashboardLoading', false)
        commit('setDashboardData', res.data)
        return res;
      })
      .catch(error => {
        commit('dashboardLoading', false)
        return error;
      })

    },
    getAllCompanies({ commit }, {url = null} = {}) {
      commit('setCompaniesLoading', true)
      url = url || "/companies";
      return axiosClient.get(url).then((res) => {
        commit('setCompaniesLoading', false)
        commit("setCompanies", res.data);
        return res;
      });
    },
    getCompanies({ commit }, {url = null} = {}) {
      commit('setCompaniesLoading', true)
      url = url || "/my-companies";
      return axiosClient.get(url).then((res) => {
        commit('setCompaniesLoading', false)
        commit("setCompanies", res.data);
        return res;
      });
    },
    getCountries({ commit }, {url = null} = {}) {
      url = url || "/countries";
      return axiosClient.get(url).then((res) => {
        commit("setCountries", res.data);
        return res;
      });
    },
    getCompany({ commit }, id) {
      commit("setCurrentCompanyLoading", true);
      return axiosClient
        .get(`/companies/${id}`)
        .then((res) => {
          commit("setCurrentCompany", res.data);
          commit("setCurrentCompanyLoading", false);
          return res;
        })
        .catch((err) => {
          commit("setCurrentCompanyLoading", false);
          throw err;
        });
    },
    saveCompany({ commit, dispatch }, company) {

      let response;
      if (company.id) {
        response = axiosClient
          .put(`/companies/${company.id}`, company)
          .then((res) => {
            commit('setCurrentCompany', res.data)
            return res;
          });
      } else {
        response = axiosClient.post("/companies", company).then((res) => {
          commit('setCurrentCompany', res.data)
          return res;
        });
      }

      return response;
    },
    deleteCompany({ dispatch }, id) {
      return axiosClient.delete(`/companies/${id}`).then((res) => {
        dispatch('getCompanies')
        return res;
      });
    },

  },
  mutations: {
    logout: (state) => {
      state.user.token = null;
      state.user.data = {};
      sessionStorage.removeItem("TOKEN");
    },

    setUser: (state, user) => {
      state.user.data = user;
    },
    setToken: (state, token) => {
      state.user.token = token;
      sessionStorage.setItem('TOKEN', token);
    },
    dashboardLoading: (state, loading) => {
      state.dashboard.loading = loading;
    },
    setDashboardData: (state, data) => {
      state.dashboard.data = data
    },
    setCountries: (state, countries) => {
      state.countries.data = countries.data
    },
    setCountriesLoading: (state, loading) => {
      state.countries.loading = loading;
    },
    setCompaniesLoading: (state, loading) => {
      state.companies.loading = loading;
    },
    setCompanies: (state, companies) => {
      state.companies.links = companies.meta.links;
      state.companies.data = companies.data;
    },
    setCurrentCompanyLoading: (state, loading) => {
      state.currentCompany.loading = loading;
    },
    setCurrentCompany: (state, company) => {
      state.currentCompany.data = company.data;
    },
    notify: (state, {message, type}) => {
      state.notification.show = true;
      state.notification.type = type;
      state.notification.message = message;
      setTimeout(() => {
        state.notification.show = false;
      }, 3000)
    },
  },
  modules: {},
});

export default store;
