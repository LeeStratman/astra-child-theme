export const activeShowcases = [123, 321, 456, 654];

/**
 * Updates images.
 *
 * @param {string} value The image object.
 * @param {number} index The index to update.
 */
export const setImages = (value, index, images, setAttributes) => {
  let selectedImagesCopy = images.slice();
  selectedImagesCopy[index] = value.sizes.full.url;
  setAttributes({ selectedImages: selectedImagesCopy });
};
