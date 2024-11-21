<script>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

export default {
    props: {
        settings: Object,
    },
    components: {
        Head,
        Header,
        Footer
    },
    data() {
        return {
            backgroundColor: this.settings.background_color || '#ffffff',
            isLoading: false, // Indica se está carregando
        };
    },
    watch: {
        backgroundColor(newColor) {
            document.body.style.backgroundColor = newColor;
        },
    },
    mounted() {
        document.body.style.backgroundColor = this.backgroundColor;
    },
    methods: {
        async saveSettings() {
            this.isLoading = true;
            try {
                await axios.post('/settings/update', {
                    background_color: this.backgroundColor,
                });
                this.$inertia.visit(route('dashboard'));
            } catch (error) {
                alert('Falha ao salvar configuração');
            } finally {
                this.isLoading = false;
            }
        },
    },
};
</script>

<template>

    <Head :title="'Configurações'" />

    <Header />

    <div class="min-h-[calc(100vh-210px)]">
        <div class="settings-container">
            <h2 class="title">Configuração de Cor de Fundo</h2>
            <form @submit.prevent="saveSettings" class="form-container">
                <div class="form-group">
                    <label for="background_color" class="label">Cor de fundo: </label>
                    <input v-model="backgroundColor" type="color" id="background_color" class="input-color" />
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-save" :disabled="isLoading">
                        <span v-if="!isLoading">Salvar</span>
                        <span v-else class="loader"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <Footer />
</template>

<style scoped>
.settings-container {
    max-width: 45%;
    margin: 40px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    text-align: left;
}

.title {
    font-size: 2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
}

.form-container {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
}

.label {
    font-size: 1rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.input-color {
    width: 100px;
    height: 40px;
    border-radius: 8px;
    border: 1px solid #ccc;
    transition: border-color 0.3s ease;
    margin-top: 8px;
}

.input-color:focus {
    border-color: #4c6ef5;
    outline: none;
}

.btn-save {
    min-height: 40px;
    min-width: 80px;
    max-height: 40px;
    max-width: 80px;
    padding: 12px 20px;
    background-color: #007bff;
    color: white;
    font-size: 1rem;
    font-weight: 600;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
}

.btn-save:hover {
    background-color: #0056b3;
}

.btn-save:focus {
    outline: none;
}

.btn-save:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

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
    .settings-container {
        padding: 15px;
        margin: 20px;
    }

    .btn-save {
        padding: 10px 16px;
    }

    .input-color {
        height: 36px;
    }
}
</style>
