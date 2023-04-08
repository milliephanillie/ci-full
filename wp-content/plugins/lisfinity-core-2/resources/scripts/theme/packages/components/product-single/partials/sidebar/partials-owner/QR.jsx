/* global lc_data, React */
/**
 * Dependencies.
 */
import ReactSVG from 'react-svg';
import QRIcon from '../../../../../../../../images/icons/qr.svg';
import { QRCode } from 'react-qrcode-logo';

const QR = (props) => {
  const { product, options } = props;

  const downloadQR = () => {
    const canvas = document.getElementById('react-qrcode-logo');
    console.log(canvas);
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
    <div className="mt-30">
      <div
        className="profile--widget-title flex items-center mb-20 py-14 px-20 -ml-20 rounded-r bg-grey-100"
        style={{
          width: 'calc(100% + 16px)',
        }}
      >
        <ReactSVG
          src={`${lc_data.dir}dist/${QRIcon}`}
          className="mr-10 w-16 h-16 fill-blue-700"
        />
        <span className="font-bold text-grey-900">Scan the code</span>
      </div>
      <QRCode value={product.qr?.['url']}
              logoImage={product.qr?.['image']?.[1] || ''}
              logoWidth={50}
              logoHeight={50}
      />
      {options?.qr_download_enabled &&
      <a onClick={downloadQR}
         className="relative left-40 cursor-pointer text-blue-700 hover:underline">{lc_data.jst[721]}</a>
      }
    </div>
  );
};

export default QR;
