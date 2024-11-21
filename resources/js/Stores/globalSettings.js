import { createStore } from 'vuex';
import axios from 'axios';

export const store = createStore({
  state: {
    backgroundColor: '#ffffff',  // Valor padrão
  },
  mutations: {
    setBackgroundColor(state, color) {
      state.backgroundColor = color;
    },
  },
  actions: {
    async fetchSettings({ commit }) {
      try {
        // Faz a requisição para a API que retorna a configuração
        const response = await axios.get('/api/settings');
        commit('setBackgroundColor', response.data.background_color);  // Salva a cor de fundo no estado
      } catch (error) {
        console.error('Erro ao carregar as configurações:', error);
      }
    },
  },
  getters: {
    backgroundColor: (state) => state.backgroundColor,  // Getter para acessar a cor de fundo
  },
});
