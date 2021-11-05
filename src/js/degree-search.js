/* global UCFDegreeSearch, Bloodhound, Handlebars */
const degreeSearchInit = ($) => {
  let degree;

  Handlebars.registerHelper('encodeString', (inputData) => {
    return new Handlebars.SafeString(inputData);
  });

  if ($('.degree-search-typeahead').length) {
    const $form = $('#degree-search');

    $form.on('submit', (e) => {
      e.preventDefault();

      const query = $('.tt-input').val(),
        target = $(e.target).attr('action'),
        url = `${target}#!/search/${query}`;

      window.location = url;
    });

    const keywords = {
      bachelor: ['bachelor\'s', 'bachelors', 'bs', 'ba', 'major', 'majors'],
      minor: ['minor', 'minors'],
      master: ['masters', 'ms', 'ma', 'mfa'],
      doctorate: ['phd', 'md', 'dpt']
    };

    let currentQuery = [''];

    const keywordReplace = (q) => {
      for (const x in q) {
        if (Object.prototype.hasOwnProperty.call(q, x)) {
          const term = q[x].toLowerCase().replace(/\. '/, '');
          for (const y in keywords) {
            if (keywords[y].indexOf(term) > 1) {
              q[x] = keywords[y];
            }
          }
        }
      }

      return q;
    };

    const customPrepare = (query, settings) => {
      const token = Bloodhound.tokenizers.whitespace(query);
      query = keywordReplace(token).join(' ');
      settings.url = settings.url.replace(/%q/, query);
      return settings;
    };

    const customQueryTokenizer = (q) => {
      let token = Bloodhound.tokenizers.whitespace(q);
      token = keywordReplace(token);
      currentQuery = token;
      return token;
    };

    const scoreSorter = (a, b) => {
      if (a.score < b.score) {
        return 1;
      }
      if (a.score > b.score) {
        return -1;
      }
      return 0;
    };

    const addMeta = (data) => {
      const q = currentQuery.join(' ');
      const exactMatch = new RegExp(`\\b${q}\\b`, 'i');
      const partialMatch = new RegExp(q, 'i');

      data.forEach((d) => {
        d.title.rendered = $(`<p>${d.title.rendered}</p>`).text();

        let matchString = '',
          score = 0;
        const titleExactMatch = exactMatch.exec(d.title.rendered) !== null,
          titlePartialMatch = partialMatch.exec(d.title.rendered) !== null;

        score += titleExactMatch ? 50 : 0;
        score += titlePartialMatch && !titleExactMatch ? 10 : 0;

        d.program_types.forEach((pt) => {
          const ptPartialMatch = partialMatch.exec(pt.name) !== null,
            ptWholeMatch = exactMatch.exec(pt.name) !== null;

          score += ptWholeMatch ? 25 : 0;
          score += ptPartialMatch && !ptWholeMatch ? 10 : 0;

          if (ptWholeMatch || ptPartialMatch) {
            matchString = `(Program Type: ${pt.name})`;
          }
        }, this);

        d.career_paths.forEach((cp) => {
          const cpWholeMatch = exactMatch.exec(cp.name) !== null;
          const cpPartialMatch = partialMatch.exec(cp.name) !== null;

          if (cpWholeMatch || cpPartialMatch) {
            matchString = `(Career Opportunity: ${cp.name})`;
          }
        }, this);

        d.score = score;
        d.matchString = matchString;
      });

      data.sort(scoreSorter);

      if (data.length === degree.limit) {
        data = data.slice(0, -1);
      }

      return data;
    };

    degree = new UCFDegreeSearch({
      transform: addMeta,
      queryTokenizer: customQueryTokenizer,
      prepare: customPrepare
    });
  }
};

if (typeof jQuery !== 'undefined') {
  jQuery(document).ready(($) => {
    if (typeof UCFDegreeSearch !== 'undefined') {
      degreeSearchInit($);
    }
  });
}
