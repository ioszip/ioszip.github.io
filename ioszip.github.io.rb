require "formula"

class IoszipGithubIo < Formula
  homepage "https://github.com/ioszip/ioszip.github.io"
  url "https://github.com/shoma2da/ioszip.github.io/archive/1.0.0.zip"
  sha1 ""

  skip_clean 'bin'

  def install
    prefix.install 'bin'
    bin.chmod 0755
  end

end
