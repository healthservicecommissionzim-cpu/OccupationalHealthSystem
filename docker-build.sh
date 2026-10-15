#!/bin/bash

# HSC-OHS Docker Build Script
echo "Building HSC-OHS Docker Image..."

# Set image name and tag
IMAGE_NAME="hsc-ohs-app"
IMAGE_TAG="latest"
REGISTRY="your-registry.com" # Update with your registry

# Build the Docker image
echo "Step 1: Building Docker image..."
docker build -t ${IMAGE_NAME}:${IMAGE_TAG} .

if [ $? -eq 0 ]; then
    echo "✓ Docker image built successfully"
else
    echo "✗ Docker build failed"
    exit 1
fi

# Tag image for registry
echo "Step 2: Tagging image for registry..."
docker tag ${IMAGE_NAME}:${IMAGE_TAG} ${REGISTRY}/${IMAGE_NAME}:${IMAGE_TAG}

# Push to registry (uncomment when ready)
# echo "Step 3: Pushing to registry..."
# docker push ${REGISTRY}/${IMAGE_NAME}:${IMAGE_TAG}

echo ""
echo "Build complete!"
echo "Image: ${IMAGE_NAME}:${IMAGE_TAG}"
echo ""
echo "To test locally, run:"
echo "  docker-compose up -d"
echo ""
echo "To push to registry, run:"
echo "  docker push ${REGISTRY}/${IMAGE_NAME}:${IMAGE_TAG}"
