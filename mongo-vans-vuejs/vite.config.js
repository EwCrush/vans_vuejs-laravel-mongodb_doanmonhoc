import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [vue(),
    {
      name: "configure-response-headers",
      configureServer: (server) => {
        server.middlewares.use((_req, res, next) => {
          res.setHeader("Cross-Origin-Embedder-Policy", "same-origin");
          res.setHeader("Cross-Origin-Opener-Policy", "same-origin");
          res.setHeader("Access-Control-Allow-Origin", "*");
          res.setHeader("Permissions-Policy", "");
          next();
        });
      },
    },
  ],
  optimizeDeps: {
    exclude: ['js-big-decimal']
  },
})
