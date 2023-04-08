/* global lc_data, React */
/**
 * External dependencies.
 */
import hammerIcon from '../../../../../../../../images/icons/construction-hammer.svg';
import ReactSVG from 'react-svg';
import AuctionTimer from './partials/AuctionTimer';
import cx from 'classnames';


function Auction(props) {
  const { product } = props;

  const classes = cx({
    'flex-col': props.style !== 'single',
  })
  return (
    <div className={`auction--infos flex ${classes}`}>

      <div className={`auction--info flex ${props.style !== 'single' && 'mb-10'}`}>
        <ReactSVG
          src={`${lc_data.dir}dist/${hammerIcon}`}
          className="relative top-2 mr-6 w-14 h-14 fill-field-icon"
        />
        <span className="lisfinity-product--meta__price text-grey-1100"
              dangerouslySetInnerHTML={{ __html: product.start_price_html }}></span>
      </div>

      <AuctionTimer product={product} style={props.style}/>

    </div>
  );
}

export default Auction;
