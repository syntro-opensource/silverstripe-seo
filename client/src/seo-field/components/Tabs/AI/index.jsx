import React, { useState } from 'react';
import PropTypes from 'prop-types';
import { Button, Spinner, Alert } from 'reactstrap';

/**
 * AI Suggestions tab for the SEO analysis field.
 *
 * Calls POST /ai-suggest/seo with the page HTML already held in context,
 * receives { metaTitle, metaDescription, focusKeyword }, and offers
 * one-click Apply buttons to populate the sibling form fields.
 */
const AITab = ({ pageId, currentTitle }) => {
  const [loading, setLoading] = useState(false);
  const [suggestions, setSuggestions] = useState(null);
  const [error, setError] = useState(null);
  const [applied, setApplied] = useState({});

  const generate = async () => {
    setLoading(true);
    setError(null);
    setSuggestions(null);
    setApplied({});

    try {
      // Grab the SS SecurityToken from the page — present on every CMS form
      const tokenField = document.querySelector('input[name="SecurityID"]');
      const tokenName = document.querySelector('input[name="SecurityToken"]');

      const res = await fetch('/ai-suggest/seo', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          pageId,
          currentTitle,
          SecurityID: tokenField?.value ?? '',
          SecurityToken: tokenName?.value ?? '',
        }),
      });

      const data = await res.json();

      if (!res.ok) {
        setError(data.error || 'Unknown error from AI service.');
      } else {
        setSuggestions(data);
      }
    } catch (err) {
      setError(`Request failed: ${err.message}`);
    } finally {
      setLoading(false);
    }
  };

  /**
   * Apply a suggestion to a CMS form field by name.
   * Uses the native value setter + input event so React picks up the change.
   */
  const applyField = (fieldName, value) => {
    const selector = `[name="${fieldName}"], #Form_EditForm_${fieldName}`;
    const input = document.querySelector(selector);
    if (!input) return;

    // React overrides the value property — use the native setter to bypass it
    const nativeSetter = Object.getOwnPropertyDescriptor(
      input.tagName === 'TEXTAREA' ? window.HTMLTextAreaElement.prototype : window.HTMLInputElement.prototype,
      'value',
    )?.set;

    if (nativeSetter) {
      nativeSetter.call(input, value);
    } else {
      input.value = value;
    }

    input.dispatchEvent(new Event('input', { bubbles: true }));
    input.dispatchEvent(new Event('change', { bubbles: true }));

    setApplied((prev) => ({ ...prev, [fieldName]: true }));
  };

  return (
    <div className="pt-3">
      <p className="text-muted small mb-3">
        Generate meta title, description and focus keyword from this page&apos;s content using AI.
        Works on draft — no need to publish first.
      </p>

      <Button
        color="primary"
        onClick={generate}
        disabled={loading}
        size="sm"
        className="mb-3"
      >
        {loading
          ? (
            <>
              <Spinner size="sm" className="me-2" />
              Generating...
            </>
          )
          : 'Generate suggestions'}
      </Button>

      {error && (
        <Alert color="danger" className="small">{error}</Alert>
      )}

      {suggestions && (
        <div className="list-group">
          <SuggestionRow
            label="Meta Title"
            value={suggestions.metaTitle}
            fieldName="MetaTitle"
            applied={applied.MetaTitle}
            onApply={applyField}
          />
          <SuggestionRow
            label="Meta Description"
            value={suggestions.metaDescription}
            fieldName="MetaDescription"
            applied={applied.MetaDescription}
            onApply={applyField}
            multiline
          />
          <SuggestionRow
            label="Focus Keyword"
            value={suggestions.focusKeyword}
            fieldName="FocusKeyword"
            applied={applied.FocusKeyword}
            onApply={applyField}
          />
        </div>
      )}
    </div>
  );
};

const SuggestionRow = ({
  label, value, fieldName, applied, onApply, multiline,
}) => (
  <div className="list-group-item">
    <div className="d-flex justify-content-between align-items-start gap-2">
      <div className="flex-grow-1">
        <div className="fw-semibold small mb-1">{label}</div>
        {multiline
          ? <p className="mb-0 small text-body-secondary">{value}</p>
          : <span className="small text-body-secondary">{value}</span>}
      </div>
      <Button
        color={applied ? 'success' : 'outline-secondary'}
        size="sm"
        onClick={() => onApply(fieldName, value)}
        style={{ whiteSpace: 'nowrap', minWidth: '70px' }}
      >
        {applied ? 'Applied' : 'Apply'}
      </Button>
    </div>
  </div>
);

AITab.defaultProps = {
  pageId: 0,
  currentTitle: '',
};

AITab.propTypes = {
  pageId: PropTypes.number,
  currentTitle: PropTypes.string,
};

SuggestionRow.defaultProps = {
  applied: false,
  multiline: false,
};

SuggestionRow.propTypes = {
  label: PropTypes.string.isRequired,
  value: PropTypes.string.isRequired,
  fieldName: PropTypes.string.isRequired,
  applied: PropTypes.bool,
  onApply: PropTypes.func.isRequired,
  multiline: PropTypes.bool,
};

export default AITab;
