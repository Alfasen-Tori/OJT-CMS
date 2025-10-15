FROM nixos/nix:2.19

WORKDIR /app

# Copy your application files
COPY . .

# Install packages using nix-env with correct package names
RUN nix-env -iA \
    nixpkgs.php82 \
    nixpkgs.php82Packages.composer \
    nixpkgs.nodejs-18_x \
    nixpkgs.nodePackages.npm

# Install dependencies
RUN npm install

# Build the application
RUN npm run build

# Clean up nix store to reduce image size
RUN nix-collect-garbage -d

# Start the application
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=$PORT"]