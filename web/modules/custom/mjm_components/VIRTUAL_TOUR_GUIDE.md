# MJM Virtual Tour Component

A powerful 360° virtual tour component built with Marzipano for Layout Builder.

## Features

- **360° Image Support**: Full spherical/equirectangular images
- **Multiple Scenes**: Connect multiple 360° views
- **Interactive Hotspots**: Navigation and information points
- **CDN Integration**: Use images from DigitalOcean Spaces or any CDN
- **Responsive Design**: Works on desktop and mobile
- **Custom Controls**: Zoom, fullscreen, auto-rotate
- **Scene Navigation**: Easy switching between tour locations

## Getting Started

### 1. Add Virtual Tour Block
1. Go to Layout Builder on any page
2. Click "Add block"
3. Find "MJM Virtual Tour" in the MJM Components category
4. Configure your tour settings

### 2. Basic Configuration

**Tour Settings:**
- **Title**: Optional tour title
- **Description**: Optional tour description  
- **Width**: Tour viewer width (e.g., `100%`, `800px`)
- **Height**: Tour viewer height (e.g., `500px`, `400px`)
- **Auto Rotate**: Enable automatic rotation
- **Show Controls**: Display zoom/fullscreen controls

### 3. Scene Configuration (JSON)

Configure your 360° scenes using JSON format:

```json
[
  {
    "id": "living_room",
    "name": "Living Room",
    "image_url": "https://your-cdn.digitaloceanspaces.com/tours/living-room-360.jpg",
    "yaw": 0,
    "pitch": 0,
    "fov": 90,
    "hotspots": [
      {
        "id": "to_kitchen",
        "yaw": 90,
        "pitch": -10,
        "type": "scene",
        "target": "kitchen",
        "text": "Kitchen"
      },
      {
        "id": "info_sofa",
        "yaw": 180,
        "pitch": -20,
        "type": "info",
        "text": "Comfortable seating area",
        "content": "This cozy living room features modern furniture and great natural lighting."
      }
    ]
  }
]
```

## Scene Properties

| Property | Description | Required |
|----------|-------------|----------|
| `id` | Unique scene identifier | Yes |
| `name` | Display name for scene | Yes |
| `image_url` | URL to 360° image | Yes |
| `yaw` | Initial horizontal rotation (degrees) | No (default: 0) |
| `pitch` | Initial vertical rotation (degrees) | No (default: 0) |
| `fov` | Initial field of view (degrees) | No (default: 90) |
| `hotspots` | Array of interactive hotspots | No |

## Hotspot Properties

| Property | Description | Required |
|----------|-------------|----------|
| `id` | Unique hotspot identifier | Yes |
| `yaw` | Horizontal position (degrees) | Yes |
| `pitch` | Vertical position (degrees) | Yes |
| `type` | Hotspot type: `scene` or `info` | Yes |
| `text` | Tooltip text | Yes |
| `target` | Target scene ID (for scene hotspots) | Conditional |
| `content` | Info panel content (for info hotspots) | Conditional |

## Image Requirements

### 360° Image Specifications
- **Format**: JPG, PNG, or WebP
- **Type**: Equirectangular projection
- **Aspect Ratio**: 2:1 (e.g., 4096×2048, 8192×4096)
- **Resolution**: Minimum 2048×1024, recommended 4096×2048 or higher
- **File Size**: Optimized for web (typically 1-5MB per image)

### DigitalOcean CDN Setup
1. Upload 360° images to your DigitalOcean Space
2. Enable CDN on your Space
3. Use CDN URLs in your scene configuration:
   ```
   https://your-space.nyc3.cdn.digitaloceanspaces.com/tours/scene1.jpg
   ```

## Example Tour Configurations

### Simple Single Scene
```json
[
  {
    "id": "main_room",
    "name": "Main Room",
    "image_url": "https://your-cdn.com/360-room.jpg",
    "yaw": 0,
    "pitch": 0,
    "fov": 90
  }
]
```

### Multi-Room Tour
```json
[
  {
    "id": "entrance",
    "name": "Entrance",
    "image_url": "https://your-cdn.com/entrance-360.jpg",
    "hotspots": [
      {
        "id": "to_living",
        "yaw": 45,
        "pitch": 0,
        "type": "scene",
        "target": "living_room",
        "text": "Living Room"
      }
    ]
  },
  {
    "id": "living_room",
    "name": "Living Room",
    "image_url": "https://your-cdn.com/living-360.jpg",
    "hotspots": [
      {
        "id": "back_entrance",
        "yaw": 225,
        "pitch": 0,
        "type": "scene",
        "target": "entrance",
        "text": "Back to Entrance"
      },
      {
        "id": "info_fireplace",
        "yaw": 90,
        "pitch": -15,
        "type": "info",
        "text": "Fireplace",
        "content": "Beautiful stone fireplace with modern gas insert."
      }
    ]
  }
]
```

## Controls & Navigation

### Built-in Controls
- **Scene Buttons**: Switch between tour locations
- **Zoom In/Out**: Adjust field of view
- **Fullscreen**: Enter fullscreen mode
- **Auto Rotate**: Toggle automatic rotation

### Mouse/Touch Controls
- **Pan**: Click and drag to look around
- **Zoom**: Mouse wheel or pinch to zoom
- **Hotspots**: Click on hotspots to navigate or view info

## Performance Tips

1. **Optimize Images**: Use compressed JPGs, typically 2-5MB per image
2. **Use CDN**: Serve images from DigitalOcean CDN for fast loading
3. **Preload**: Keep hotspot count reasonable (5-10 per scene)
4. **Mobile**: Test on mobile devices for performance

## Troubleshooting

### Common Issues

**Tour doesn't load:**
- Check image URLs are accessible
- Verify JSON syntax is valid
- Ensure images are equirectangular format

**Hotspots not working:**
- Verify hotspot coordinates (yaw/pitch)
- Check target scene IDs exist
- Validate hotspot JSON structure

**Performance issues:**
- Reduce image file sizes
- Use fewer hotspots per scene
- Enable CDN delivery

### Browser Support
- Chrome 60+
- Firefox 55+
- Safari 11+
- Edge 79+

## Creating 360° Images

### Equipment Options
1. **360° Cameras**: Insta360, Ricoh Theta, GoPro MAX
2. **DSLR + Fisheye**: Manual stitching required
3. **Smartphone Apps**: Google Street View, 360 Camera apps

### Processing Software
- **PTGui**: Professional stitching software
- **Adobe Lightroom**: With panorama merge
- **Hugin**: Free open-source option
- **Kolor Autopano**: Professional solution

## Support

For technical support or feature requests, contact the development team or refer to the Marzipano documentation at [marzipano.net](https://www.marzipano.net/).
