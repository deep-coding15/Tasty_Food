import { defineConfig } from "vite";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
  plugins: [tailwindcss()],
  server: {
    port: 3000,
    strictPort: false,
    host: "localhost",
    open: true,
  },
  build: {
    outDir: "dist", //les fichiers générés (HTML, JS, CSS optimisés) seront placés dans le dossier dist/.
    emptyOutDir: true, // le dossier dist/ est vidé avant chaque build.
  },
  resolve: {
    alias: {
      "@": "/src",
    },
  },
});

