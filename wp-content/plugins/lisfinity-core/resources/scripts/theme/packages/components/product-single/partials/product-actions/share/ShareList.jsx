/* global lc_data, React */
/**
 * External dependencies.
 */
import { useState, useEffect, Fragment } from 'react';
import { __ } from '@wordpress/i18n';
import {
  FacebookShareButton,
  FacebookIcon,
  LinkedinShareButton,
  LinkedinIcon,
  TwitterShareButton,
  TwitterIcon,
  TelegramShareButton,
  TelegramIcon,
  WhatsappShareButton,
  WhatsappIcon,
  PinterestShareButton,
  PinterestIcon,
  VKShareButton,
  VKIcon,
  OKShareButton,
  OKIcon,
  RedditShareButton,
  RedditIcon,
  TumblrShareButton,
  TumblrIcon,
  LivejournalShareButton,
  LivejournalIcon,
  MailruShareButton,
  MailruIcon,
  ViberShareButton,
  ViberIcon,
  WorkplaceShareButton,
  WorkplaceIcon,
  LineShareButton,
  LineIcon,
  PocketShareButton,
  PocketIcon,
  InstapaperShareButton,
  InstapaperIcon,
  EmailShareButton,
  EmailIcon,
} from 'react-share';

function ShareList(props) {
  const { product, currentUser } = props;
  const { share } = product;
  const options = {
    size: 36,
    round: false,
    borderRadius: 4,
  };

  return (
    <Fragment>
      <h6 className="shares--label font-bold mb-10 px-30">{lc_data.jst[517]}</h6>
      <div className="shares flex flex-wrap py-40 pt-0 px-30 -mb-6 -mx-4">
        {share.indexOf('facebook') > -1 &&
        <FacebookShareButton url={product.guid}><FacebookIcon {...options}/></FacebookShareButton>}
        {share.indexOf('twitter') > -1 &&
        <TwitterShareButton url={product.guid}><TwitterIcon {...options}/></TwitterShareButton>}
        {share.indexOf('linkedin') > -1 &&
        <LinkedinShareButton url={product.guid}><LinkedinIcon {...options}/></LinkedinShareButton>}
        {share.indexOf('telegram') > -1 &&
        <TelegramShareButton url={product.guid}><TelegramIcon {...options}/></TelegramShareButton>}
        {share.indexOf('whatsapp') > -1 &&
        <WhatsappShareButton url={product.guid}><WhatsappIcon {...options}/></WhatsappShareButton>}
        {share.indexOf('pinterest') > -1 &&
        <PinterestShareButton url={product.guid} media={product.thumbnail.url}><PinterestIcon
          {...options}/></PinterestShareButton>}
        {share.indexOf('vk') > -1 && <VKShareButton url={product.guid}><VKIcon {...options}/></VKShareButton>}
        {share.indexOf('odnoklas') > -1 && <OKShareButton url={product.guid}><OKIcon {...options}/></OKShareButton>}
        {share.indexOf('reddit') > -1 &&
        <RedditShareButton url={product.guid}><RedditIcon {...options}/></RedditShareButton>}
        {share.indexOf('tumblr') > -1 &&
        <TumblrShareButton url={product.guid}><TumblrIcon {...options}/></TumblrShareButton>}
        {share.indexOf('livejournal') > -1 &&
        <LivejournalShareButton url={product.guid}><LivejournalIcon {...options}/></LivejournalShareButton>}
        {share.indexOf('mailru') > -1 &&
        <MailruShareButton url={product.guid}><MailruIcon {...options}/></MailruShareButton>}
        {share.indexOf('viber') > -1 &&
        <ViberShareButton url={product.guid}><ViberIcon {...options}/></ViberShareButton>}
        {share.indexOf('workplace') > -1 &&
        <WorkplaceShareButton url={product.guid}><WorkplaceIcon {...options}/></WorkplaceShareButton>}
        {share.indexOf('line') > -1 && <LineShareButton url={product.guid}><LineIcon {...options}/></LineShareButton>}
        {share.indexOf('pocket') > -1 &&
        <PocketShareButton url={product.guid}><PocketIcon {...options}/></PocketShareButton>}
        {share.indexOf('instapaper') > -1 &&
        <InstapaperShareButton url={product.guid}><InstapaperIcon {...options}/></InstapaperShareButton>}
        {share.indexOf('email') > -1 &&
        <EmailShareButton url={product.guid}><EmailIcon {...options}/></EmailShareButton>}
      </div>
    </Fragment>
  );
}

export default ShareList;
