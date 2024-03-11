import { defineConfig } from 'vite'
import react from '@vitejs/plugin-react'

// https://vitejs.dev/config/
export default defineConfig({
  server: {
    proxy:{
      '/api': 'http://79.133.183.21:8080'
    }
  },
  plugins: [react()],
})