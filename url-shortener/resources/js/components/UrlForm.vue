<template>
  <div class="url-form-container">
    <div class="url-form-content bg-cover">
      <header class="header">
        URL Shortener
      </header>
      <form @submit.prevent="submitUrl" class="space-y-4">
        <div>
          <label for="url" class="label">Enter URL</label>
          <input 
            id="url" 
            type="url" 
            v-model="originalUrl" 
            class="input-field" 
            placeholder="https://example.com" 
            required 
          />
        </div>

        <div v-if="shortUrl" class="mt-4">
          <label for="shortUrl" class="label">Short URL</label>
          <div class="relative mt-1 flex items-center">
            <div
              id="shortUrl"
              class="hyperlink-input"
              @click="openShortUrl"
            >
              {{ shortUrl }}
            </div>
            <button
              @click="copyToClipboard(shortUrl)"
              class="copy-button"
            >
              <img v-if="!copySuccess" :src="copyIcon" alt="Copy Icon" class="icon" />
              <img v-else :src="successIcon" alt="Success Icon" class="icon" />
            </button>
          </div>
        </div>

        <button 
          type="submit" 
          class="submit-button-hover submit-button bn18"
        >
          Shorten URL
        </button>
      </form>

      <div v-if="errorMessage" class="mt-4">
        <p class="error-message">{{ errorMessage }}</p>
      </div>
    </div>
  </div>
</template>


<script>
import axios from 'axios';
import copyIcon from '../../img/copy.png';  
import successIcon from '../../img/checkbox.png';  

export default {
  data() {
    return {
      originalUrl: '',
      prefix: '',
      shortUrl: '',
      errorMessage: '',
      copySuccess: false,
      copyIcon: copyIcon,
      successIcon: successIcon,
    };
  },
  methods: {
    async submitUrl() {
      this.errorMessage = '';

      if (!this.isValidUrl(this.originalUrl)) {
        this.errorMessage = 'Please enter a valid URL';
        return;
      }

      try {
        const response = await axios.post('/shorten', {
          original_url: this.originalUrl,
          prefix: this.prefix
        });
        this.shortUrl = response.data.short_url;
      } catch (error) {
        if (error.response && error.response.data) {
          if (error.response.data.errors) {
            this.errorMessage = error.response.data.errors.original_url
              ? error.response.data.errors.original_url[0]
              : 'Validation error';
          } else if (error.response.data.error) {
            this.errorMessage = error.response.data.error;
          } else {
            this.errorMessage = 'An error occurred';
          }
        } else {
          this.errorMessage = 'An error occurred';
        }
      }
    },
    isValidUrl(url) {
      try {
        new URL(url);
        return true;
      } catch (_) {
        return false;
      }
    },
    copyToClipboard(text) {
      navigator.clipboard.writeText(text).then(() => {
        this.copySuccess = true;
        setTimeout(() => {
          this.copySuccess = false;
        }, 2000);
      }).catch(err => {
        console.error('Failed to copy: ', err);
      });
    },
    openShortUrl() {
      window.open(this.shortUrl, '_blank');
    },
  }
};
</script>

<style scoped>
.url-form-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
  background-color: #f3f4f6; 
  background-image: url('/img/background3.png'); 
  background-size: cover;
  background-position: center;
  font-family: 'Segoe UI','Helvetica Neue', sans-serif;
}

.url-form-content {
  background-color: rgba(255, 255, 255, 0.95);
  padding: 20px;
  border-radius: 8px;
  width: 100%;
  max-width: 600px;
  box-shadow: 0 0 20px rgba(0, 0, 0, 0.1); 
  position: relative;
}

.header {
  width: calc(100% + 40px);
  padding: 1rem;
  background-color: #25aae1; 
  color: #ffffff; 
  text-align: center;
  font-size: 2rem;
  font-weight: bold;
  border-radius: 8px 8px 0 0;
  box-shadow: 0 4px 15px 0 rgba(49, 196, 190, 0.75); 
  margin-left: -20px; 
  margin-right: -20px; 
  margin-top: -20px; 
  margin-bottom: 20px; 
}

.label {
  display: block;
  font-size: 1.25rem;
  font-weight: medium;
  color: #000000; 
  margin-bottom: 0.25rem;
}

.input-field {
  width: 100%;
  padding: 0.75rem;
  font-size: 1.25rem;
  border: 1px solid #cbd5e0; 
  border-radius: 0.375rem;
  margin-bottom: 1rem;
}

.hyperlink-input {
  position: relative;
  color: #3182ce; 
  padding: 0.75rem;
  text-decoration: underline;
  cursor: pointer;
  font-size: 1.25rem;
}

.copy-button {
  position: absolute;
  right: 0.5rem;
  top: 50%;
  transform: translateY(-50%);
  color: #3182ce;
  background-color: #edf2f7; 
  border-radius: 0.375rem;
  padding: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.icon {
  width: 2rem;
  height: 2rem;
}

.error-message {
  color: #e53e3e;
}

.submit-button {
  width: 100%;
  font-size: 1.5rem;
  font-weight: 600;
  color: #fff;
  cursor: pointer;
  display: block;
  margin: auto;
  height: 3.5rem;
  text-align: center;
  border: none;
  background-size: 300% 100%;
  border-radius: 2rem;
  transition: all 0.4s ease-in-out;
}

.submit-button-hover:hover {
  background-position: 100% 0;
  transition: all 0.4s ease-in-out;
}

.submit-button-hover:focus {
  outline: none;
}

.submit-button-hover.bn18 {
  background-image: linear-gradient(
    to right,
    #25aae1,
    #40e495,
    #30dd8a,
    #2bb673
  );
  box-shadow: 0 4px 15px 0 rgba(49, 196, 190, 0.75);
}

@media (max-width: 600px) {
  .url-form-content {
    padding: 10px;
  }

  .input-field {
    width: 100%;
    margin-top: 0;
  }
  .submit-button {
    font-size: 1rem;
  }
}
</style>
