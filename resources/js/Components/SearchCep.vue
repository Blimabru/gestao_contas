<template>
  <div class="cep-container">
    <form @submit.prevent="consultarCep" class="form-cep">
      <div class="form-group">
        <label for="cep" class="label-cep">Buscar endereço por CEP: </label>
        <input type="text" id="cep" v-model="cep" placeholder="Digite o CEP" maxlength="9" class="input-cep" required
          @input="formatCep" />
      </div>

      <button type="submit" class="btn-consultar" :disabled="isLoading">
        <span v-if="!isLoading">Consultar</span>
        <span v-else class="loader"></span>
      </button>
    </form>

    <!-- Exibição de erro -->
    <div v-if="errorMessage" class="error-message">
      <p>{{ errorMessage }}</p>
    </div>

    <div v-if="dadosCep" class="result">
      <h3>Dados do CEP:</h3>
      <div class="cep-info">
        <p><i class="fas fa-map-marker-alt"></i> <strong>CEP:</strong> {{ dadosCep.cep }}</p>
        <p><i class="fas fa-road"></i> <strong>Logradouro:</strong> {{ dadosCep.logradouro }}</p>
        <p><i class="fas fa-building"></i> <strong>Bairro:</strong> {{ dadosCep.bairro }}</p>
        <p><i class="fas fa-city"></i> <strong>Cidade:</strong> {{ dadosCep.localidade }}</p>
        <p><i class="fas fa-flag"></i> <strong>Estado:</strong> {{ dadosCep.uf }}</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      cep: '',
      dadosCep: null,
      errorMessage: '',
      isLoading: false, // Controla o estado de carregamento
    };
  },
  methods: {
    async consultarCep() {
      this.isLoading = true; // Inicia o carregamento
      try {
        const response = await fetch(`/api/consulta-cep`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({ cep: this.cep }),
        });

        if (!response.ok) {
          const errorData = await response.json();
          throw new Error(errorData.error || 'Erro ao consultar o CEP');
        }

        const dados = await response.json();
        if (dados.erro) {
          throw new Error('CEP não encontrado');
        }

        this.dadosCep = dados;
      } catch (error) {
        this.errorMessage = error.message;
        this.dadosCep = null;
      } finally {
        this.isLoading = false; // Finaliza o carregamento
      }
    },

    // Função para formatar o CEP (exemplo: 12345-678)
    formatCep() {
      this.cep = this.cep.replace(/\D/g, '');
      if (this.cep.length > 5) {
        this.cep = this.cep.slice(0, 5) + '-' + this.cep.slice(5, 9);
      }
    }
  },
};
</script>

<style scoped>
/* Estilo do container */
.cep-container {
  margin-top: 20px;
  padding: 20px;
  background-color: #fff;
  border-radius: 10px;
  height: fit-content;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
  box-sizing: border-box;
  text-align: left;
}

/* Estilo do formulário */
.form-cep {
  display: flex;
  flex-direction: column;
}

/* Estilo dos grupos de campos do formulário */
.form-group {
  margin-bottom: 10px;
}

.label-cep {
  font-size: 1rem;
  color: #333;
  margin: 8px 0;
  font-weight: 600;
}

.input-cep {
  width: 100%;
  margin: 10px 0;
  padding: 10px;
  font-size: 1rem;
  border: 1px solid #ccc;
  border-radius: 5px;
  box-sizing: border-box;
  transition: border-color 0.3s;
}

.input-cep:focus {
  border-color: #007bff;
  outline: none;
}

.btn-consultar {
  background-color: #007bff;
  color: white;
  font-size: 1rem;
  padding: 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
  display: flex;
  justify-content: center;
  align-items: center;
}

.btn-consultar:hover {
  background-color: #0056b3;
}

.btn-consultar:focus {
  outline: none;
}

.btn-consultar:disabled {
  background-color: #ccc;
  cursor: progress;
}

/* Estilo de erro */
.error-message {
  color: red;
  font-size: 1rem;
  margin-top: 20px;
  text-align: center;
}

/* Estilo do resultado da consulta */
.result {
  margin-top: 20px;
  font-size: 1rem;
}

.result h3 {
  font-size: 1.2rem;
  margin-bottom: 10px;
}

.cep-info {
  font-size: 1rem;
  line-height: 1.6;
}

.fas {
  width: 25px;
  height: 25px;
  text-align: center;
}

.cep-info p {
  margin: 8px 0;
  font-size: 1rem;
  color: #333;
}

.cep-info strong {
  color: #007bff;
}

/* Animação de carregamento */
.loader {
  border: 4px solid #f3f3f3;
  border-top: 4px solid #007bff;
  border-radius: 50%;
  width: 20px;
  height: 20px;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  0% {
    transform: rotate(0deg);
  }

  100% {
    transform: rotate(360deg);
  }
}

/* Responsividade */
@media (max-width: 768px) {
  .cep-container {
    padding: 15px;
    width: 90%;
    margin-left: auto;
    margin-right: auto;
  }

  .input-cep {
    font-size: 0.9rem;
    padding: 8px;
  }

  .btn-consultar {
    font-size: 0.9rem;
    padding: 10px;
  }

  .label-cep {
    font-size: 0.9rem;
  }

  .cep-info {
    font-size: 0.9rem;
  }

  .cep-info p {
    font-size: 0.9rem;
  }
}

@media (max-width: 480px) {
  .cep-container {
    padding: 10px;
    width: 100%;
  }

  .input-cep {
    font-size: 0.8rem;
    padding: 6px;
  }

  .btn-consultar {
    font-size: 0.8rem;
    padding: 8px;
  }

  .label-cep {
    font-size: 0.8rem;
  }

  .cep-info {
    font-size: 0.8rem;
  }

  .cep-info p {
    font-size: 0.8rem;
  }
}
</style>
