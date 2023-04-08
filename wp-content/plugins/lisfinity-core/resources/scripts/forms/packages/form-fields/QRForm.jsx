/**
 * External dependencies.
 */
import { useDispatch, useSelector } from 'react-redux';
import { useEffect, useState } from '@wordpress/element';
import { isEmpty } from 'lodash';
import { QRCode } from 'react-qrcode-logo';
import MediaSingleImage from './MediaSingleImage';
import Input from './Input';
import { updateCosts, updateFormData } from '../../../dashboard/packages/store/actions';
import { sprintf } from '@wordpress/i18n';
import { formatMoney } from '../../../theme/vendor/functions';

const QRForm = (props) => {
  const {
    allData,
    user,
    type,
    id,
    name,
    field,
    label,
    description,
    payment_package,
    error,
  } = props;

  const [value, setValue] = useState(props.value);
  const [qr, setQr] = useState(false);
  const data = useSelector(state => state);
  const dispatch = useDispatch();

  useEffect(() => {
    setQr(data?.paymentPackage?.promotion_qr || data?.paymentPackage?.payment_package?.promotion_qr);
  }, [data?.paymentPackage?.payment_package?.promotion_qr]);

  const onChange = (id, value, type) => {
    const newData = data;
    if (!newData.formData[name]) {
      newData.formData[name] = {};
    }
    if (type === 'image') {
      if (!newData.formData[name][type]) {
        newData.formData[name][type] = [];
      }
      newData.formData[name][type] = [value[0], value[1]];
    } else {
      if (!newData.formData[name][type]) {
        newData.formData[name][type] = '';
      }
      newData.formData[name][type] = value;
    }

    setValue(value);

    dispatch(updateFormData(newData.formData));
  };

  useEffect(() => {
    const { costs } = data;
    if (!costs['media']) {
      costs['media'] = {};
    }
    if (!costs['total']) {
      costs['total'] = {};
      if (!costs['total']['media']) {
        costs['total']['media'] = 0;
      }
    }
    if (!isEmpty(value) && (costs['media']['qr'] !== parseFloat(qr?.price))) {
      costs['media']['qr'] = parseFloat(qr?.price) || 0;
      costs['total']['media'] += parseFloat(qr?.price) || 0;
      costs['final'] = costs.total.media + (parseFloat(qr?.price) || 0);
    }

    dispatch(updateCosts(costs));
  }, [value]);

  const downloadQR = () => {
    const canvas = document.getElementById('react-qrcode-logo');
    if (canvas) {
      const pngUrl = canvas
        .toDataURL('image/png')
        .replace('image/png', 'image/octet-stream');
      let downloadLink = document.createElement('a');
      downloadLink.href = pngUrl;
      downloadLink.download = 'qr.png';
      document.body.appendChild(downloadLink);
      downloadLink.click();
      document.body.removeChild(downloadLink);
    }
  };

  return (
    <div className="flex flex-col w-full">
      <MediaSingleImage
        allData={allData}
        field={allData.img_data}
        field_type="single_image"
        name={`${name}-image`}
        id={`${name}-image`}
        value={isEmpty(data?.formData[name]) ? [] : data?.formData}
        label={allData?.labels[0]}
        onChange={e => onChange('image', isEmpty(data?.formData[`${name}-image_url`]) ? [] : [data?.formData[`${name}-image`], data?.formData[`${name}-image_url`]], 'image')}
      />
      <Input
        display
        id={`${name}-url`}
        name={`${name}-url`}
        label={allData.labels[1]}
        handleChange={e => {
          onChange('url', e.target.value, 'url');
        }}
        value={data?.formData?.[name]?.['url']}
        attributes={allData.attributes}
        additional={allData.additional}
      />
      {!isEmpty(value) &&
      <div style={{ marginTop: -30, marginBottom: 20 }}>
        <QRCode
          value={data?.formData?.[name]?.['url']}
          logoImage={data?.formData?.[name]?.['image']?.[1] || ''}
          logoWidth={50}
          logoHeight={50}
          level={'H'}
          includeMargin={true}
        />
        <a onClick={downloadQR}
           className="relative left-40 cursor-pointer text-blue-700 hover:underline">{lc_data.jst[721]}</a>
      </div>
      }
      {qr && !isEmpty(value) &&
      <div className="flex items-center">
        <div
          className="ml-auto py-6 px-10 bg-green-100 border border-green-300 rounded font-bold text-green-800"
          dangerouslySetInnerHTML={{
            __html: qr?.price_html,
          }}></div>
      </div>
      }
    </div>
  );
};

export default QRForm;
