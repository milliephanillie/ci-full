// eslint-disable-next-line no-unused-vars
import '@styles/admin';
// import 'airbnb-browser-shims'; // Uncomment if needed

// Your code goes here ...
const listing = lc_data.product_listing;
const paymentPackage = lc_data.payment_package;
const { promotion, commission, payment_subscription } = lc_data;
const listingMeta = document.getElementById('carbon_fields_container_product_information1');
const packageMeta = document.getElementById('carbon_fields_container_package_information');
const promotionMeta = document.getElementById('carbon_fields_container_promotion_information');
const subscriptionMeta = document.getElementById('carbon_fields_container_payment_subscription_information');
const postExcerpt = document.getElementById('postexcerpt');
const productType = $('#product-type');
productType.change((e) => {
  // display meta boxes depending on a screen.
  listingMeta.classList.add('is-hidden');
  packageMeta.classList.add('is-hidden');
  promotionMeta.classList.add('is-hidden');
  subscriptionMeta.classList.add('is-hidden');
  postExcerpt.classList.remove('hide-if-js');
  if (listing === e.target.value) {
    listingMeta.classList.remove('is-hidden');
  }
  if (paymentPackage === e.target.value) {
    packageMeta.classList.remove('is-hidden');
  }
  if (payment_subscription === e.target.value) {
    subscriptionMeta.classList.remove('is-hidden');
  }
  if (promotion === e.target.value) {
    promotionMeta.classList.remove('is-hidden');
  }
  if (listing === e.target.value || paymentPackage === e.target.value || promotion === e.target.value || commission === e.target.value) {
    postExcerpt.classList.add('hide-if-js');
  }
  if (commission === e.target.value) {
    $('._sale_price_field, ._stock_custom_field').addClass('hide-if-js');
  } else {
    $('._sale_price_field, ._stock_custom_field').removeClass('hide-if-js');
  }
  // for Inventory tab
  if (promotion !== e.target.value && paymentPackage !== e.target.value && payment_subscription !== e.target.value) {
    $('#inventory_product_data ._sold_individually_field input[type=checkbox]').prop('checked', true);
  } else {
    $('#inventory_product_data ._sold_individually_field input[type=checkbox]').prop('checked', false);
  }

  if (listing === e.target.value || commission === e.target.value || promotion === e.target.value || paymentPackage === e.target.value || payment_subscription === e.target.value) {
    $('#_virtual').prop('checked', true);
  }
});
productType.change();
// for Price tab
$('.general_options').addClass(`show_if_simple show_if_grouped show_if_variable show_if_${listing} show_if_${paymentPackage} show_if_${promotion} show_if_${commission} show_if_${payment_subscription}`);
$('.options_group.pricing').addClass(`show_if_${listing} show_if_${paymentPackage} show_if_${promotion} show_if_${commission} show_if_${payment_subscription}`).show();

// hide not needed tabs
$('.shipping_options').addClass(`hide_if_${listing} hide_if_${paymentPackage} hide_if_${promotion} hide_if_${commission} hide_if_${payment_subscription}`);
$('.linked_product_options').addClass(`hide_if_${listing} hide_if_${paymentPackage} hide_if_${promotion} hide_if_${commission} hide_if_${payment_subscription}`);
$('.attribute_options').addClass(`hide_if_${listing} hide_if_${paymentPackage} hide_if_${promotion} hide_if_${commission} hide_if_${payment_subscription}`);
$('.variations_options').addClass(`hide_if_${listing} hide_if_${paymentPackage} hide_if_${promotion} hide_if_${commission} hide_if_${payment_subscription}`);
$('.advanced_options').addClass(`hide_if_${listing} hide_if_${paymentPackage} hide_if_${promotion} hide_if_${commission} hide_if_${payment_subscription}`);

// copy to clipboard
const copyToClipboard = (str) => {
  const el = document.createElement('textarea');
  el.value = str;
  document.body.appendChild(el);
  el.select();
  document.execCommand('copy');
  document.body.removeChild(el);
};

function displayTaxonomies() {
  const productCategory = document.getElementsByName('carbon_fields_compact_input[_product-category]');
  const taxonomies = JSON.parse(lc_data.taxonomies);
  if (productCategory && 'listing' === $('#product-type').val()) {
    const productCategoryValue = productCategory[0].value;
    const defaultPostBoxes = ['submit', 'postimage', 'woocommerce-product-images', 'carbon_fields_container_product_videos'];
    $('#side-sortables').children().each((i, child) => {
      const id = $(child).attr('id');
      const value = id.replace('div', '');
      $(child).hide();
      // organize taxonomies display
      if (taxonomies[productCategoryValue]) {
        if (taxonomies['common'].includes(value) || taxonomies[productCategoryValue].includes(value) || defaultPostBoxes.includes(value)) {
          $(child).show();
        }
      } else {
        if (taxonomies['common'].includes(value) || defaultPostBoxes.includes(value)) {
          $(child).show();
        }
      }
    });
  } else {
    $('#side-sortables').children().each((i, child) => {
      const defaultPostBoxes = ['submitdiv', 'postimagediv', 'woocommerce-product-images', 'product_catdiv', 'tagsdiv-product_tag'];
      const id = $(child).attr('id');
      $(child).hide();
      if (defaultPostBoxes.includes(id)) {
        $(child).show();
      }
    });
  }
}

document.addEventListener('DOMContentLoaded', () => {
  displayTaxonomies();
  $('select[name="carbon_fields_compact_input[_product-category]"]').on('change', () => {
    displayTaxonomies();
  });
  $('#product-type').on('change', () => {
    displayTaxonomies();
  });

  // copy custom tags to clipboard
  const clickToCopy = document.querySelectorAll('.click-to-copy');
  clickToCopy.forEach((el) => {
    el.addEventListener('click', () => {
      copyToClipboard(el.dataset.value);
      const copySpan = document.createElement('span');
      const copyText = document.createTextNode('Copied!');
      copySpan.appendChild(copyText);
      el.appendChild(copySpan);
      setTimeout(() => {
        copySpan.remove();
      }, 1000);
    });
  });

  const dateInput = document.querySelector('.cf-datetime__input');
  if (dateInput) {
    dateInput.setAttribute('autocomplete', 'off');
  }
});
